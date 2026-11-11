# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`longman/telegram-bot`: a pure PHP library (no framework) wrapping the Telegram Bot API. Namespace `Longman\TelegramBot\` maps to `src/`. PHP `^8.1`, CI runs 8.1 through 8.5. `composer.lock` is not committed.

## Commands

```bash
composer install
composer check-code                     # phpcs, PSR-12 over src/, tests/, utils/ (must print nothing)
composer test                           # phpunit, whole suite
vendor/bin/phpunit tests/Unit/Entities/MessageTest.php
vendor/bin/phpunit --filter testGetCommand
vendor/bin/phpcbf                       # auto-fix coding standard violations
composer test-cov                       # coverage (needs Xdebug), writes clover.xml
composer db-prepare                     # drops and recreates telegrambot and telegrambot_migrations on the server DB_HOST and DB_PORT name (default 127.0.0.1:3306), so point it at a throwaway server
composer schema-diff                    # compares those two databases: exit 0 clean, 1 differences, 2 errors
```

Test database: `tests/Unit/ConversationTest.php` and `tests/Unit/DBTest.php` touch MySQL. They read the `PHPUNIT_DB_*` constants from the PHPUnit config and expect a database (default name `telegrambot`) loaded from `structure.sql`. To use other credentials, copy `phpunit.xml.dist` to `phpunit.xml` (gitignored) and edit the constants there. `TestHelpers::emptyDb()` deletes every row from the core tables, so never point it at a database holding real data.

CI runs every job against its own fresh database server: PHP 8.1 through 8.5 on MariaDB 12.3, and PHP 8.5 on MariaDB 10.11, MySQL 9.7 and MySQL 8.4. On each of these four servers it runs the migration chain and the schema diff (see Database schema), then the test suite against the `telegrambot` database that `composer db-prepare` built. MySQL is stricter than MariaDB (for example, it rejects a foreign key that references a non-unique column), so keep `structure.sql` loadable on all four with default settings.

The PHPUnit config defines the constant `PHPUNIT_TESTSUITE`. When it is defined, `Request::send()` returns a fake `ServerResponse` built by `Request::generateGeneralFakeServerResponse()` and never calls the network, so tests need no HTTP mocking and cannot assert on real API behavior.

## Architecture

Four static or central classes in `src/` do most of the work:

- `Telegram` is the entry object the library user constructs. It owns command discovery, the admin list, and the update loop (`handle()` for webhooks, `handleGetUpdates()` for polling). Its constructor calls `Request::initialize($this)`.
- `Request` is fully static. API methods are not real PHP methods: `Request::sendPhoto([...])` goes through `__callStatic` into `send()`, which validates the name against the `$actions` whitelist. The `@method` docblock on the class is the only declaration. Fields that accept file uploads are listed in `$input_file_fields`, and parameterless methods in `$actions_need_dummy_param`.
- `DB` is fully static, MySQL through PDO, and optional (`Telegram::enableMySql()`). Table names are global constants (`TB_MESSAGE`, `TB_USER`, ...) defined at init from `DB::defineTables()` with the user's table prefix. Every method starts with an `isDbConnected()` guard and returns `false` when the database is off, so the library works without it.
- `TelegramLog` is a static PSR-3 facade with three streams (error, debug, update); see `docs/01-utils.md`.

### Update flow

`Telegram::processUpdate()` is the router:

1. Run the optional update filter callback.
2. Build the command list by scanning every commands path for `*Command.php` files and `require`-ing them. Admin command paths are added only when the sender is in the admin list.
3. Pick a command name. For a `message` update it is the `/command` text, or the message type (for example `new_chat_members` resolves to `NewchatmembersCommand`). For any other update type it is the type with underscores removed (`callback_query` resolves to `CallbackqueryCommand`). The fallback is `genericmessage`; an unknown `/command` falls to `generic`.
4. Drop duplicates by update id, then `DB::insertRequest()` persists the update.
5. `executeCommand()` calls `Command::preExecute()`, which handles the `need_mysql` and `private_only` checks before `execute()`.

### Commands

`Command` has three abstract children that define authorization: `SystemCommand`, `AdminCommand`, `UserCommand`. Resolution order is System, then Admin (admins only), then User. A class is found either through `Telegram::addCommandClass()` or by convention: file `FooCommand.php` in a commands path, class `FooCommand`, with the namespace read from the file itself. Command names are the lowercased class name minus `Command`, so `FooBarCommand` is `/foobar`.

A command with an empty `$usage` is treated as non-executable by the message router. `Command::__call` relays unknown methods to the current `Update`, so `$this->getMessage()` inside a command is `$this->getUpdate()->getMessage()`.

The library ships only a minimal set of commands. Full examples live in the separate `php-telegram-bot/example-bot` repository.

### Entities

Every Telegram type is a class under `src/Entities/` extending `Entity`. They hold almost no code:

- Data is stored as dynamic fields; getters are magic. `getFooBar()` is handled by `Entity::__call`, which reads the `foo_bar` key. The `@method` docblock on each class is the declaration, so adding a field means adding a docblock line.
- `subEntities()` maps a field name to the class it should be hydrated into (`['from' => User::class]`, or `[PhotoSize::class]` wrapped in an array for lists). A nested object that is missing from this map is returned as a raw array.
- Polymorphic types (`ChatMember`, `MessageOrigin`, `ReactionType`, `MenuButton`, `ChatBoostSource`, `Message`) point `subEntities()` at a `Factory` subclass whose `make()` picks the concrete class from a discriminator field.
- Magic setters work only on `InlineEntity`, `InputMedia`, `Keyboard` and `KeyboardButton`; everything else is read-only after construction.
- `ServerResponse` wraps every API reply and hydrates `result` into the right entity based on the keys present in the payload.

### Adding Bot API support

A new API version usually touches several places that must stay in step:

- New type: entity class with `@method` docblock and `subEntities()`, plus the parent entity's map.
- New method: `Request::$actions` and the `@method` docblock on `Request`.
- New update type: `Update` constant and `subEntities()` entry. If it is persisted, also a `DB::insert*Request()` method wired into `DB::insertRequest()`, the table in `DB::defineTables()`, the `telegram_update` column, `structure.sql`, and a migration.

### Database schema

`structure.sql` is the full current schema. Each release that changes it also adds `utils/db-schema-update/<from>-<to>.sql`; during development the file is named `<last release>-unreleased.sql` and linked from the `[Unreleased]` notes in `CHANGELOG.md`. On each of the four CI servers, `composer db-prepare` loads the current `structure.sql` into `telegrambot` and the 0.44.1 schema plus every migration in order into `telegrambot_migrations`, stopping at the first file that fails. `composer schema-diff` then compares the two through `information_schema`: tables, columns, indexes, and foreign keys by column (not comments or column order). It fails on any difference not listed in `utils/db-schema-diff-accepted.txt`, and on any entry there that no longer matches a difference. Both must pass, so a schema change needs both files edited to the same result. Adding an entry to the accepted list needs the maintainer's agreement.

Every migration is plain SQL that runs unchanged on all four servers: no stored procedures and no client commands (`source`, `delimiter`). MariaDB-only syntax such as `DROP FOREIGN KEY IF EXISTS` fails on MySQL, and generated key names differ between servers, so look a key up by column in `information_schema` and apply the change through `PREPARE`, as `0.83.1-0.84.0.sql` does.

To run both scripts locally, start a throwaway server and point them at its port: `docker run -d --name tg-db -p 127.0.0.1:33123:3306 -e MARIADB_ROOT_PASSWORD=root mariadb:12.3`, then `DB_PORT=33123 DB_PASSWORD=root composer db-prepare` and `DB_PORT=33123 DB_PASSWORD=root composer schema-diff`.

## Conventions

- PSR-12, enforced by `phpcs.xml.dist`.
- Every file starts with the package license header and every class and method has a docblock (see `CONTRIBUTING.md` for the exact text).
- Pull requests target `dev`, the default branch (git flow). Record user-visible changes under `[Unreleased]` in `CHANGELOG.md`, crediting the author handle and PR number in the existing style.
- Version format is `0.<version>.<hotfix>`. The version string lives in `Telegram::$version` and the `branch-alias` in `composer.json`.
- Design records for larger changes live in `docs/specs/` and are committed, because they explain why the code is the way it is. Step-by-step implementation plans are working files: keep them in `docs/plans/`, which is ignored, and never commit them.

## Releasing

Packagist publishes every pushed tag within a minute, so a tag is the release. Tags can be created only by repository admins and can never be moved or deleted. Worked example, releasing 0.84.0 after 0.83.1:

1. On `dev`, make one commit titled `Version 0.84.0`:
   - set `Telegram::$version` to `0.84.0`;
   - rename `utils/db-schema-update/0.83.1-unreleased.sql` to `0.83.1-0.84.0.sql` (skip when the release has no migration);
   - in `CHANGELOG.md`, turn `[Unreleased]` into `## [0.84.0] - <date>`, add a fresh empty `[Unreleased]` block above it, and update the link definitions at the bottom: `[0.84.0]` compares `0.83.1...0.84.0`, and `[0.84.0-sql-migration]` points at the file under `tree/0.84.0/`.
2. Push and wait for the `Tests` workflow to pass on that commit.
3. Tag that exact commit `0.84.0` (lightweight, no `v` prefix) and push the tag.
4. Create the GitHub release `Version 0.84.0` for the tag, with the changelog section as notes and the reference-style links written out in full.
5. Fast-forward `master` to the tag. `master` only ever moves to release commits.
6. On `dev`, raise the `dev-dev` branch alias in `composer.json` to the next version line (`0.85.x-dev`).

Use a hotfix number (`0.84.1`) only for fixes that change no dependency constraint and need no migration.
