#!/usr/bin/env bash
#
# This file is part of the TelegramBot package.
#
# (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# Create the two databases the schema diff compares, on one server:
#   telegrambot             from the current structure.sql;
#   telegrambot_migrations  from the 0.44.1 structure.sql, then every file in
#                           utils/db-schema-update in ls order.
# Both are dropped and created again, so the script can run repeatedly on one
# server. The 0.44.1 schema is read through git, fetching the tag from origin
# when it is missing; the working tree is never touched.
#
# Connection over TCP from DB_HOST (default 127.0.0.1), DB_PORT (3306),
# DB_USER (root) and DB_PASSWORD; the client is DB_CLIENT (mysql). Waits up to
# 120 s for the server. Stops at the first failing file and names it on
# standard error.

set -euo pipefail

# Sort the migration files byte by byte, whatever the caller's locale.
export LC_ALL=C

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_USER="${DB_USER:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"
DB_CLIENT="${DB_CLIENT:-mysql}"

baseline_tag=0.44.1
root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

work="$(mktemp -d)"
trap 'rm -rf "$work"' EXIT

# Progress goes to standard error, redrawn in place, and only when it is a terminal.
progress() {
    if [ -t 2 ]; then
        printf '\r\033[K%s' "$1" >&2
    fi
}

end_progress() {
    if [ -t 2 ]; then
        printf '\r\033[K' >&2
    fi
}

fail() {
    end_progress
    echo "db-prepare: $1" >&2
    exit 1
}

# The password goes through an option file, so it shows neither in the process
# list nor in a client warning. --defaults-file keeps other option files out.
(
    umask 077
    printf '[client]\npassword="%s"\n' "${DB_PASSWORD//\\/\\\\}" > "$work/client.cnf"
)

db() {
    "$DB_CLIENT" --defaults-file="$work/client.cnf" --protocol=TCP --host="$DB_HOST" --port="$DB_PORT" \
        --user="$DB_USER" --default-character-set=utf8mb4 "$@"
}

# run_sql <label> <client arguments>: SQL comes from standard input or --execute.
# On failure prints the client's error and stops, naming the label.
run_sql() {
    local label=$1
    shift

    if db "$@" > /dev/null 2> "$work/client.err"; then
        if [ -s "$work/client.err" ]; then
            end_progress
            cat "$work/client.err" >&2
        fi
        return 0
    fi

    end_progress
    cat "$work/client.err" >&2
    fail "$label failed; later files were not applied."
}

if ! command -v "$DB_CLIENT" > /dev/null; then
    fail "database client '$DB_CLIENT' not found; set DB_CLIENT."
fi

if ! git -C "$root" rev-parse --quiet --verify "refs/tags/$baseline_tag^{commit}" > /dev/null; then
    progress "Fetching tag $baseline_tag from origin"
    if ! git -C "$root" fetch --quiet --no-tags origin "refs/tags/$baseline_tag:refs/tags/$baseline_tag"; then
        fail "cannot fetch tag $baseline_tag from origin."
    fi
fi

if ! git -C "$root" show "refs/tags/$baseline_tag:structure.sql" > "$work/structure-$baseline_tag.sql"; then
    fail "cannot read structure.sql of tag $baseline_tag."
fi

deadline=$((SECONDS + 120))
until db --execute='SELECT 1' > /dev/null 2> "$work/client.err"; do
    if [ "$SECONDS" -ge "$deadline" ]; then
        end_progress
        cat "$work/client.err" >&2
        fail "no database server answered at $DB_HOST:$DB_PORT within 120 s."
    fi

    progress "Waiting for the database server at $DB_HOST:$DB_PORT, $((deadline - SECONDS)) s left"
    sleep 1
done

for database in telegrambot telegrambot_migrations; do
    progress "Recreating $database"
    run_sql "recreating $database" --execute="DROP DATABASE IF EXISTS \`$database\`; CREATE DATABASE \`$database\`"
done

progress "telegrambot: structure.sql"
run_sql "structure.sql on telegrambot" --database=telegrambot < "$root/structure.sql"

progress "telegrambot_migrations: structure.sql of $baseline_tag"
run_sql "structure.sql of $baseline_tag on telegrambot_migrations" --database=telegrambot_migrations \
    < "$work/structure-$baseline_tag.sql"

migrations=("$root"/utils/db-schema-update/*)
total=${#migrations[@]}
started=$SECONDS

for index in "${!migrations[@]}"; do
    file=${migrations[$index]}
    name=${file##*/}

    left=""
    if [ "$index" -gt 0 ]; then
        left=", about $(((SECONDS - started) * (total - index) / index)) s left"
    fi

    progress "telegrambot_migrations: $((index + 1))/$total $name$left"
    run_sql "$name on telegrambot_migrations" --database=telegrambot_migrations < "$file"
done

end_progress
echo "Created telegrambot from structure.sql and telegrambot_migrations from $baseline_tag plus $total files on $DB_HOST:$DB_PORT."
