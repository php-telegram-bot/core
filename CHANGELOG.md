# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

Exclamation symbols (:exclamation:) note something of importance e.g. breaking changes. Click them to learn more.

## [Unreleased]
### Added
- New entities, methods, update types and inline keyboard button for Payments (Bot API 3.0).
### Changed
- [:exclamation:][unreleased-correct-printerror] Corrected `ServerResponse->printError` method to print by default and return by setting `$return` parameter.
### Deprecated
### Removed
### Fixed
### Security

## [0.47.1] - 2017-08-06
### Added
- Linked version numbers in changelog for easy verification of code changes. 
### Fixed
- Private-only commands work with edited messages now too.

## [0.47.0] - 2017-08-06 [YANKED]
### Changed
- Updated readme to latest state of 0.47.0.
### Fixed
- `Telegram::enableAdmin()` now handles duplicate additions properly.
- `Request::getMe()` failure doesn't break cron execution any more.
### Security
- [:exclamation:][0.47.0-private-only-admin-commands] New command parameter `$private_only` to enforce usage in private chats only (set by default for Admin commands).

## [0.46.0] - 2017-07-15
### Added
- Callbacks can be added to be executed when callback queries are called.
- New Bot API 3.1 changes (#550).
- `/cleanup` command for admins, that cleans out old entries from the DB.
### Changed
- [:exclamation:][0.46.0-bc-request-class-refactor] Big refactor of the `Request` class, removing most custom method implementations.

## [0.45.0] - 2017-06-25
**Note:** After updating to this version, you will need to execute the [SQL migration script][0.45.0-sql-migration] on your database.
### Added
- Documents can be sent by providing its contents via Psr7 stream (as opposed to passing a file path).
- Allow setting a custom Guzzle HTTP Client for requests (#511).
- First implementations towards Bots API 3.0.
### Changed
- [:exclamation:][0.45.0-bc-chats-params-array] `Request::sendToActiveChats` and `DB::selectChats` now accept parameters as an options array and allow selecting of channels.
### Deprecated
- Deprecated `Message::getNewChatMember()` (Use `Message::getNewChatMembers()` instead to get an array of all newly added members).
### Removed
- [:exclamation:][0.45.0-bc-up-download-directory] Upload and download directories are not set any more by default and must be set manually.
- [:exclamation:][0.45.0-bc-remove-deprecated-methods] Completely removed `Telegram::getBotName()` and `Entity::getBotName()` (Use `::getBotUsername()` instead).
- [:exclamation:][0.45.0-bc-remove-deprecated-methods] Completely removed deprecated `Telegram::unsetWebhook()` (Use `Telegram::deleteWebhook()` instead).
### Fixed
- ID fields are now typed with `PARAM_STR` PDO data type, to allow huge numbers.
- Message type data type for PDO corrected.
- Indexed table columns now have a fitting length.
- Take `custom_input` into account when using getUpdates method (mainly for testing).
- Request limiter has been fixed to correctly support channels.

## [0.44.1] - 2017-04-25
### Fixed
- Erroneous exception when using webhook without a database connection.

## [0.44.0] - 2017-04-25
### Added
- Proper standalone `scrutinizer.yml` config.
- Human-readable `last_error_date_string` for debug command.
### Changed
- Bot username no longer required for object instantiation.
### Removed
- All examples have been moved to a [dedicated repository][example-bot].
### Fixed
- [:exclamation:][0.44.0-bc-update-content-type] Format of Update content type using `$update->getUpdateContent()`.

## [0.43.0] - 2017-04-17
### Added
- Travis CI webhook for Support Bot.
- Interval for request limiter.
- `isRunCommands()` method to check if called via `runCommands()`.
- Ensure coding standards for `tests` folder with `phpcs`.
### Changed
- Move default commands to `examples` folder.
- All links point to new organisation repo.
- Add PHP 7.1 support and update dependencies.
### Fixed
- Prevent handling the same Telegram updates multiple times, throw exception instead.

## [0.42.0] - 2017-04-09
### Added
- Added `getBotId()` to directly access bot ID.
### Changed
- Rename `bot_name` to `bot_username` everywhere.
### Deprecated
- Deprecated `Telegram::getBotName()` (Use `Telegram::getBotUsername()` instead).
### Fixed
- Tests are more reliable now, using a properly formatted API key.

## [0.41.0] - 2017-03-25
### Added
- `$show_in_help` attribute for commands, to set if it should be displayed in the `/help` command.
- Link to new Telegram group: `https://telegram.me/PHP_Telegram_Bot_Support`
- Introduce change log.

## [0.40.1] - 2017-03-07
### Fixed
- Infinite message loop, caused by incorrect Entity variable.

## [0.40.0] - 2017-02-20
### Added
- Request limiter for incoming requests.
### Fixed
- Faulty formatting in logger.

## [0.39.0] - 2017-01-20
### Added
- Newest bot API changes.
- Allow direct access to PDO object (`DB::getPdo()`).
- Simple `/debug` command that displays various system information to help debugging.
- Crontab-friendly script.
### Changed
- Botan integration improvements.
- Make logger more flexible.
### Fixed
- Various bugs and recommendations by Scrutinizer.

## [0.38.1] - 2016-12-25
### Fixed
- Usage of self-signed certificates in conjunction with the new `allowed_updates` webhook parameter.

## [0.38.0] - 2016-12-25
### Added
- New `switch_inline_query_current_chat` option for inline keyboard.
- Support for `channel_post` and `edited_channel_post`.
- New alias `deleteWebhook` (for `unsetWebhook`).
### Changed
- Update WebhookInfo entity and `setWebhook` to allow passing of new arguments.

## [0.37.1] - 2016-12-24
### Fixed
- Keyboards that are built without using the KeyboardButton objects.
- Commands that are called via `/command@botname` by correctly passing them the bot name.

## [0.37.0] - 2016-12-13
### Changed
- Logging improvements to Botan integration.
### Deprecated
- Move `hideKeyboard` to `removeKeyboard`.

[unreleased-correct-printerror]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#correct-printerror
[0.47.0-private-only-admin-commands]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#private-only-admin-commands
[0.46.0-bc-request-class-refactor]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#request-class-refactor
[0.46.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/0.45.0/utils/db-schema-update/0.44.1-0.45.0.sql
[0.45.0-bc-remove-deprecated-methods]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#remove-deprecated-methods
[0.45.0-bc-chats-params-array]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#chats-params-array
[0.45.0-bc-up-download-directory]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#up-download-directory
[0.44.0-bc-update-content-type]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#update-getupdatecontent
[example-bot]: https://github.com/php-telegram-bot/example-bot

[Unreleased]: https://github.com/php-telegram-bot/core/compare/master...develop
[0.47.1]: https://github.com/php-telegram-bot/core/compare/0.47.0...0.47.1
[0.47.0]: https://github.com/php-telegram-bot/core/compare/0.46.0...0.47.0
[0.46.0]: https://github.com/php-telegram-bot/core/compare/0.45.0...0.46.0
[0.45.0]: https://github.com/php-telegram-bot/core/compare/0.44.1...0.45.0
[0.44.1]: https://github.com/php-telegram-bot/core/compare/0.44.0...0.44.1
[0.44.0]: https://github.com/php-telegram-bot/core/compare/0.43.0...0.44.0
[0.43.0]: https://github.com/php-telegram-bot/core/compare/0.42.0...0.43.0
[0.42.0]: https://github.com/php-telegram-bot/core/compare/0.41.0...0.42.0
[0.41.0]: https://github.com/php-telegram-bot/core/compare/0.40.1...0.41.0
[0.40.1]: https://github.com/php-telegram-bot/core/compare/0.40.0...0.40.1
[0.40.0]: https://github.com/php-telegram-bot/core/compare/0.39.0...0.40.0
[0.39.0]: https://github.com/php-telegram-bot/core/compare/0.38.1...0.39.0
[0.38.1]: https://github.com/php-telegram-bot/core/compare/0.38.0...0.38.1
[0.38.0]: https://github.com/php-telegram-bot/core/compare/0.37.1...0.38.0
[0.37.1]: https://github.com/php-telegram-bot/core/compare/0.37.0...0.37.1
[0.37.0]: https://github.com/php-telegram-bot/core/compare/0.36...0.37.0
