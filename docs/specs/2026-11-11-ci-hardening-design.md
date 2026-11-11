# CI hardening: MySQL upgrade path, database matrix, schema diff, static analysis

Status: approved 2026-11-11.

## Intent

CI is the judge for every change to this library, whoever writes it. Today it
misses three classes of error that were found by hand in November 2026:

- The migration chain was only ever run on MariaDB. On MySQL it has been broken
  since 0.61.0, so MySQL users never had a clean upgrade path.
- Nothing compares a migrated database with a fresh one, so the two schemas
  drifted apart unnoticed.
- No static analysis runs. An undefined variable, a wrong argument type and an
  implicit nullable parameter all reached a release.

The goal is that each of these fails a pull request instead of reaching users.

## Goals

1. An existing database can be upgraded from 0.44.1 to the current schema on
   MySQL as well as MariaDB, with the same files.
2. CI runs on the database versions people should be using now.
3. CI fails when a migrated database and a fresh one differ in structure.
4. CI fails on new static analysis errors.

## Non-goals

- Removing the leftover `botan_shortener` table or the `message.new_chat_member`
  column. That was decided earlier: the migration does not drop user data.
- Making every historical migration safe to re-run. Only the files that are
  repaired get that property.
- Fixing the existing static analysis findings. They go into a baseline.
- Replacing the per-release SQL files with a migration tool.

## Anti-goals

- A repaired migration must not change what a MariaDB user ends up with.
- The schema diff must not be loosened until it passes. Every accepted
  difference is listed by name with a reason.

## Constraints

- PHP `^8.1`; the PHP matrix stays 8.1 through 8.5.
- Migrations are plain `.sql` files that users run with their own client. No
  stored procedures that need extra privileges, no client-specific commands.
- The MySQL 9.7 client rejects `source` passed through `-e`. CI must feed SQL
  files through standard input, which works on every client.
- Branch protection requires the single job named `Tests`. Every new job must
  be a dependency of that job.

## Approaches considered

1. **Repair the old files in place (chosen).** Rewrite the failing statements
   so one chain runs on both servers, with the lookup-then-`PREPARE` pattern of
   `0.83.1-0.84.0.sql`. One set of files, one chain to test. Editing a released
   migration has precedent (0.83.1 patched `0.82.0-0.83.0.sql`). Risk: changing
   the MariaDB result, which the schema diff guards.
2. **Leave the old files and add a MySQL catch-up script.** History untouched,
   but a MySQL database can be stuck at several points, and the script would
   have to detect each one. More code, harder to test.
3. **Adopt a migration tool.** Solves it properly, at the cost of a dependency
   and a new workflow for every user. A separate project.

Approach 1 was picked by the maintainer on 2026-11-11.

## Design

### 1. Repaired migrations

Known failures on MySQL 8.4 and 9.7 (the client stops at the first error in a
file, so more may appear once these are fixed):

| File | Statement | Why MySQL refuses |
|---|---|---|
| `0.60.0-0.61.0.sql` | `DROP KEY message_id` on `telegram_update` | the index backs a foreign key, even with `FOREIGN_KEY_CHECKS=0` |
| `0.62.0-0.63.0.sql` | `DROP PRIMARY KEY` on `poll_answer` | the key backs a foreign key |
| `0.62.0-0.63.0.sql` | `DROP FOREIGN KEY IF EXISTS`, `DROP INDEX IF EXISTS` | MariaDB-only syntax |
| `0.82.0-0.83.0.sql` | column `via_bot` unknown | consequence of the file above stopping early |

Rules for a repaired file:

- It runs unchanged on MariaDB and MySQL.
- It can be run again on a database where an earlier attempt stopped part-way.
  This is what lets a stuck MySQL user recover: re-run the file that failed,
  then continue the chain.
- Foreign keys are found by column in `information_schema`, never by a
  generated name such as `message_ibfk_6`.

Worked example. A MySQL user upgraded 0.62.0 to 0.63.0 in 2020. The four
`poll` columns were added, then `DROP PRIMARY KEY` failed and the client
stopped. Their `message` table has no `via_bot`. After this change they re-run
`0.62.0-0.63.0.sql`: the `poll` columns are detected as present and skipped,
the primary key is rebuilt, `via_bot` is added, and every later file applies.

Proof that MariaDB is unaffected: before touching a file, dump the migrated
MariaDB schema; after the repair the dump must be identical.

### 2. Database matrix

| Server | Version | Why |
|---|---|---|
| MariaDB | 12.3 | current LTS |
| MariaDB | 10.11 | oldest LTS still maintained |
| MySQL | 9.7 | current LTS |
| MySQL | 8.4 | previous LTS, still maintained |

MariaDB 10.3, which CI uses today, is past end of life and is dropped.

Every PHP version runs against MariaDB 12.3 (five jobs). The other three
servers run on the newest PHP only (three jobs). Eight jobs instead of five.
Each job loads the fresh schema, runs the full chain from 0.44.1, runs the
schema diff, and runs the test suite.

### 3. Schema diff

A script under `utils/` compares two databases on one server through
`information_schema` and prints every difference:

- tables present on one side only;
- per column: type, nullability, default, character set and collation;
- indexes with their columns and uniqueness;
- foreign keys by column and referenced column, ignoring generated names.

Column comments and column order are not compared (maintainer may overrule;
see open calls). Accepted differences live in one small file next to the
script, each with a reason:

- table `botan_shortener` (migrated only): unused, kept on purpose;
- column and index `message.new_chat_member` (migrated only): same;
- `user.username` length 255 against 191: shrinking a column is not done
  silently.

The script exits non-zero on any difference not in that file, and also when an
entry in that file no longer matches anything, so the list cannot go stale.

### 4. Static analysis

PHPStan as a dev dependency, a `composer analyse` script, and one CI job on the
newest PHP. It analyses `src/` with a generated baseline, so existing findings
do not fail the build and new ones do. The level is the highest at which the
baseline stays reviewable; the builder measures and proposes it.

### Documentation

`CLAUDE.md` (commands, the database section) and `CHANGELOG.md` are updated.
The changelog tells MySQL users which file to re-run if an old upgrade stopped.

## Calls made by the author, open to correction

- Comments and column order are left out of the diff. Comparing them would
  mean rewriting about 25 column definitions on users' largest tables for a
  cosmetic gain.
- `user.username` stays on the accepted list instead of being shrunk.
- MySQL 8.0 is not tested: it left support in April 2026.
- PHPStan runs once, not per PHP version.

## Left to the builder

- The exact SQL of each repair, and any further failures hidden behind the
  first ones.
- Whether the diff script is PHP or shell plus SQL.
- The PHPStan level and whether a few findings are cheaper to fix than to
  baseline.
- How the workflow is split into jobs, as long as `Tests` depends on all.
