# Webhook secret token check

Status: approved 2026-11-19.

## Intent

`Telegram::handle()` processes any JSON body posted to the webhook URL. Nothing
shows that the request came from Telegram, so whoever knows the URL can post an
update of their own making. The library then trusts every field of it:

- the sender id decides whether admin commands are available, so an update
  that carries an admin's id reaches them (measured on 2026-11-18: a forged
  `/debug` message from an enabled admin's id resolved to
  `AdminCommands\DebugCommand`, the same message from another id found no
  command);
- the same holds for every command a bot defines, for any user;
- any field can carry a value Telegram would never send, which is what made
  the SQL injection of GHSA-8h9r-j624-v4jx reachable.

Telegram offers a remedy: `setWebhook` takes a `secret_token`, and Telegram
then sends it in the `X-Telegram-Bot-Api-Secret-Token` header of every webhook
request. `Telegram::setWebhook()` already forwards that parameter, but nothing
compares the header on the way back, so a bot owner who sets a token today is
protected by nothing.

The goal is that a bot owner can make `handle()` refuse every request that does
not carry their token, with two lines of setup.

## Goals

1. A bot with a secret token configured refuses a webhook request whose header
   is missing or different, before the request body is read, logged or stored.
2. The token is configured once per script and used both when registering the
   webhook and when handling it, so the two cannot drift apart by accident.
3. The README shows the token as part of the standard webhook setup, and tells
   owners of running bots in which order to roll it out.

## Non-goals

- Checking the sender's IP address against Telegram's ranges. The ranges
  change, and a stale list rejects real updates.
- Servers that do not fill `$_SERVER` for each request (long-running workers
  that pass the body through `setCustomInput()`). Their owners have a request
  object and compare the header themselves; the README says so.
- Setting an HTTP status code for a refused request. `handle()` sets none
  today, and the hook script owns the response.
- Validating the types of update fields. That is a separate, larger change.
- Changes to the example bot and to `telegram-bot-manager`, which are separate
  repositories. The manager already compares this header itself.

## Anti-goals

- A bot with no token configured must behave exactly as it does today. The
  check cannot be on by default, because the library has no token to compare
  with until the owner supplies one.
- The expected token must never appear in an exception message or a log line.

## Constraints

- PHP `^8.1`.
- Telegram accepts a token of 1 to 256 characters from `A-Z`, `a-z`, `0-9`,
  `_` and `-` (Bot API, `setWebhook`, read on 2026-11-18).
- `set.php` and `hook.php` are separate requests, so the token given to
  `setWebhook()` is not available to `handle()` unless the hook script
  configures it too.
- The change is developed in the private fork of the advisory, where no CI
  runs, and ships in the same release as the injection fix.

## Approaches considered

1. **A setter on `Telegram` and a check at the top of `handle()` (chosen).**
   Small, opt-in, and the comparison lives in the library instead of in every
   bot.
2. **Documentation only.** Each owner writes their own header comparison in
   `hook.php`. The gap stays in the library, and a naive `==` or `===`
   comparison is what most would write.
3. **Port the guards of `telegram-bot-manager` into the core** (URL secret, IP
   allowlist, token). A much larger surface, and the IP list goes stale.

Approach 1 was picked by the maintainer on 2026-11-18.

## Design

### Configuration

`Telegram` gains a secret token, empty by default, with a setter and a getter
in the style of `setCustomInput()` and `getCustomInput()`:

- `setWebhookSecretToken(string $secret_token): Telegram`
- `getWebhookSecretToken(): string`

The setter throws a `TelegramException` for a value Telegram would refuse: an
empty string, more than 256 characters, or any character outside the allowed
set. The whole string is checked, so a token that ends in a newline is refused
too.

### Handling a webhook request

When a token is configured, `handle()` compares it with the request header
before it reads the body. The header value comes from
`$_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']`. A missing header, a value
that is not a string, or a value that differs makes `handle()` throw a
`TelegramException` with a fixed message. The comparison uses `hash_equals`,
so its duration does not depend on how many leading characters match.

The check sits before `Request::getInput()` and `TelegramLog::update()`, so a
refused request leaves nothing in the update log or the database.

The check applies whenever a token is configured, also when the body was
supplied through `setCustomInput()`.

### Registering the webhook

When a token is configured and the `$data` passed to `setWebhook()` holds no
`secret_token`, `setWebhook()` sends the configured one. A `secret_token`
passed in `$data` wins.

### Worked example

A bot owner picks the token `Kq7_x-41` and adds one line to each script:

```php
// set.php
$telegram->setWebhookSecretToken('Kq7_x-41');
$telegram->setWebhook($hook_url);

// hook.php
$telegram->setWebhookSecretToken('Kq7_x-41');
$telegram->handle();
```

| Request to `hook.php` | Before | After |
|---|---|---|
| From Telegram, header `Kq7_x-41` | processed | processed |
| Forged, no header | processed | `TelegramException`, nothing read or stored |
| Forged, header `guess` | processed | `TelegramException`, nothing read or stored |
| Any request, bot without a configured token | processed | processed |

### Rolling it out on a running bot

The order matters. If `hook.php` starts checking before Telegram knows the
token, every real update is refused and Telegram retries them until they
expire.

1. Add the setter to `set.php` and run it. Telegram now sends the header, and
   `hook.php` ignores it.
2. Add the setter to `hook.php`.

The README gives these two steps.

### Documentation

- `README.md`: the setter in the `set.php` and `hook.php` examples, a short
  section on securing the webhook with the rollout order and the note for
  servers that do not fill `$_SERVER`.
- `CHANGELOG.md`: an entry under Added, and a note under Security that a bot
  stays open to forged requests until its owner configures a token.
- `CLAUDE.md`: one sentence in the update flow.

## Proof

- Unit tests for the four rows of the table above, for the setter's refusals,
  and for `setWebhook()` sending the configured token. Each is seen failing
  before the code exists.
- The forged admin request of 2026-11-18 is replayed with a token configured
  and must be refused.
- No CI runs in the private fork, so the whole suite, `composer check-code`
  and `composer analyse` are run locally. `Tests` runs on `dev` after the
  merge and must pass before the release is tagged.

## Calls made by the author, open to correction

- A refused request throws a plain `TelegramException`, as `handle()` already
  does for an empty body. A dedicated exception class is left out until a use
  for telling the two apart is named.
- The setter validates the token, so a typing mistake fails in `set.php`
  instead of at Telegram.
- A `secret_token` in the `$data` of `setWebhook()` wins over the configured
  one. An owner who passes two different tokens gets what they asked for.
- No warning is logged when a bot handles a webhook without a token. It would
  be written on every request of every existing bot.
- The branch is based on the injection fix, because both changes add to the
  same changelog section and the advisory is said to accept only one pull
  request into the default branch of its fork. That limit comes from a summary
  of GitHub's documentation and was not tested.

## Left to the builder

- The property name and the wording of the exception messages.
- How a test observes what `setWebhook()` sends: under `PHPUNIT_TESTSUITE`,
  `Request::send()` returns a fake reply and does not expose the request data.
- Where in `handle()` the comparison lives (inline or a protected method).
