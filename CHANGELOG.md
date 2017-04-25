# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- Proper standalone `scrutinizer.yml` config.
- Human-readable last error date for debug command.
### Changed
- Bot username no longer required for object instantiation.
### Deprecated
### Removed
- All examples have been moved to a [dedicated repository](https://github.com/php-telegram-bot/example-bot).
### Fixed
- Format of Update content type using `$update->getUpdateContent()`.
### Security

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
- Deprecated `getBotName()` (Use `getBotUsername()` instead).
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
