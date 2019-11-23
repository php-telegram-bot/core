# Changelog
The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

Exclamation symbols (:exclamation:) note something of importance e.g. breaking changes. Click them to learn more.

## [Unreleased]
### Notes
- [:ledger: View file changes][Unreleased]
### Added
### Changed
### Deprecated
### Removed
### Fixed
### Security

## [0.61.1] - 2019-11-23
### Notes
- [:ledger: View file changes][0.61.1]
### Added
- Tests for schema update scripts, to ensure that updates work as expected. (#1025)
### Fixed
- Parameter `inline_query_id` in `InlineQuery::answerInlineQuery`. (#1021)
- Corrected DB schema update 0.60.0-0.61.0. (#1025)

## [0.61.0] - 2019-11-02
### Notes
- [:ledger: View file changes][0.61.0] ∙ [:page_with_curl: DB migration script][0.61.0-sql-migration]
- :exclamation: Built-in logging (Monolog) has been removed, a custom PSR-3 logger must be used now! (see #964 for more info)
### Added
- Code snippet in `GenericmessageCommand` to keep obsolete service message system commands working. (#999)
- Static boolean property `SystemCommand::$execute_deprecated` (must be assigned before handling the request) to try and execute any deprecated system command. (#999)
- Improved MySQL DB index for `message` table, making the cleanup much faster on bigger databases. (Thanks to @damianperez) (#1015)
- `/cleanup` command now supports dry run which simply outputs all queries that would be run. (#1015)
### Changed
- Small readme and code fixes / simplifications. (#1001)
- Upgrade PHPUnit to 8.x and PHPCS to 3.5. For tests now minimum PHP version is 7.2. (#1008)
- Updated updates log importer (requires PHP7+). (#1009)
### Removed
- Service message system commands, which are now handled by `GenericmessageCommand`. (#999)
- [:exclamation:][0.61.0-bc-remove-monolog-from-core] Monolog has been removed as built-in logging engine. (#1009)
- Assets have been moved to a dedicated repository. (#1012)
### Fixed
- Boolean value for Polls gets saved correctly in MySQL DB. (#996)
- Correctly use `Request::answerInlineQuery` in `InlineQuery::answer`. (#1001)
- PSR-12 incompatibilities in the codebase. (#1008)
- Improved and corrected `/cleanup` command. (#1015)

## [0.60.0] - 2019-08-16
### Notes
- [:ledger: View file changes][0.60.0]
### Added
- Bot API 4.4 (Animated stickers, `ChatPermissions`). (#990)
### Changed
- Allow passing native entity objects to requests. (#991)
### Fixed
- Non-existing commands now correctly execute `GenericCommand` again. (#993)

## [0.59.1] - 2019-07-18
### Notes
- [:ledger: View file changes][0.59.1]
- :exclamation: Deprecated logging method will be removed in the next version, update to PSR-3 now! (see #964 for more info)
### Added
- Issue labels set automatically via templates.
### Changed
- Logging only updates or only debug/errors is now possible. (#983)
### Fixed
- Don't output deprecation notices if no logging is enabled. (#983)
- Respect custom HTTP Client initialisation, no matter when it is set. (#986)

## [0.59.0] - 2019-07-07
### Notes
- [:ledger: View file changes][0.59.0]
### Changed
- Touched up readme including header and badges. (#981)
- Updated changelog to be more useful for external integrations like [Tidelift] and GitHub releases. (#981)
### Removed
- Abstract methods from `InputMedia` interface, as method annotations didn't work for propagation. (#978)

## [0.58.0] - 2019-06-26
### Notes
- [:ledger: View file changes][0.58.0] ∙ [:page_with_curl: DB migration script][0.58.0-sql-migration]
- Logging now uses [PSR-3] `LoggerInterface`. Learn more about how to use it here: #964
### Added
- New funding and support details. (#971)
- Custom issue templates. (#972)
- Bot API 4.3 (Seamless Telegram Login, `LoginUrl`) (#957)
- `reply_markup` field to `Message` entity. (#957)
### Changed
- Use PSR-12 for code style. (#966)
- Some general housekeeping. (#972)
- [:exclamation:][0.58.0-bc-return-value-of-empty-entity-properties] Return an empty array for Entity properties with no items, instead of `null`. (#969)
- `TelegramLog` now adheres to [PSR-3] `LoggerInterface` and allows custom logger implementations. (#964)
### Deprecated
- Old logging that uses Monolog still works but will be removed in the near future. Use `TelegramLog::initialize($logger, $update_logger);` from now on. (#964)
- [:exclamation:][0.58.0-bc-startcommand-is-now-a-usercommand] `StartCommand` is now a `UserCommand` (not `SystemCommand` any more). (#970)
### Removed
- Botan.io integration completely removed. (#968)
### Fixed
- `forward_date` is now correctly saved to the DB. (#967)
- Broken `StickerSet::getStickers()` method. (#969)
- Smaller code and docblock fixes. (#973)
### Security
- Security disclosure managed by [Tidelift]. (#971)
- Don't allow a user to call system commands directly. (#970)

## [0.57.0] - 2019-06-01
### Notes
- [:ledger: View file changes][0.57.0] ∙ [:page_with_curl: DB migration script][0.57.0-sql-migration]
- :grey_exclamation: This is a big update and involves a bunch of MySQL database updates, so please *review the changelog carefully*.

### Added
- New logo! :tada: (#954)
- Bot API 4.2 (Polls). (#948)
- `getIsMember()` method to `ChatMember` entity. (#948)
- `getForwardSenderName()` method to `Message` entity. (#948)
- `forward_sender_name` (and forgotten `forward_signature`) DB fields. (#948)
- Added missing API fields to Entities and DB. (#885)
- Created database tables for `shipping_query` and `pre_checkout_query`. (#885)
### Fixed
- Missing DB table name specifier in `/cleanup` command. (#947)

## [0.56.0] - 2019-04-15
### Notes
- [:ledger: View file changes][0.56.0] ∙ [:page_with_curl: DB migration script][0.56.0-sql-migration]
- :grey_exclamation: This is a big update, so please *review the changelog carefully*!
### Added
- Helper for sending `InputMedia` objects using `Request::sendMediaGroup()` and `Request::editMediaMessage()` methods.
- Allow passing absolute file path for InputFile fields, instead of `Request::encodeFile($path)`. (#934)
### Changed
- All Message field types dynamically search for an existing Command class that can handle them. (#940)
- Upgrade dependencies. (#945)
### Deprecated
- Botan.io service has been discontinued. (#925)
- Most built-in System Commands will be handled by GenericmessageCommand by default in a future release and will require a custom implementation. (#940)
### Fixed
- Constraint errors in `/cleanup` command. (#930)
- Return correct objects for requests. (#934)
- PHPCS: static before visibility declaration. (#945)

## [0.55.1] - 2019-01-06
### Notes
- [:ledger: View file changes][0.55.1]
### Added
- Add missing `Request::editMessageMedia()` and `CallbackQuery::getChatInstance()` methods. (#916)
### Fixed
- Return correct message type. (#913)

## [0.55.0] - 2018-12-20
### Notes
- [:ledger: View file changes][0.55.0] ∙ [:page_with_curl: DB migration script][0.55.0-sql-migration]
### Added
- Bot API 4.0 and 4.1 (Telegram Passport) (#870, #871)
- Test PHP 7.3 with Travis. (#903)
### Changed
- [:exclamation:][0.55.0-bc-move-animation-out-of-games-namespace] Move Animation entity out of Games namespace.
### Fixed
- Added missing `video_note` to `Message` types.

## [0.54.1] - 2018-10-23
### Notes
- [:ledger: View file changes][0.54.1]
### Fixed
- `sendToActiveChats` now works correctly for any valid Request action. (#898)

## [0.54.0] - 2018-07-21
### Notes
- [:ledger: View file changes][0.54.0] ∙ [:page_with_curl: DB migration script][0.54.0-sql-migration]
### Added
- `ChatAction` class to simplify chat action selection. (#859)
- Telegram Games platform! (#732)
- Ability to set custom MySQL port. (#860)
- New `InvalidBotTokenException` exception. (#855)
### Changed
- [:exclamation:][0.54.0-bc-rename-constants] Rename and ensure no redefinition of constants: `BASE_PATH` -> `TB_BASE_PATH`, `BASE_COMMANDS_PATH` -> `TB_BASE_COMMANDS_PATH`. (#813)
- Improve readability of readme code snippets. (#861)
### Fixed
- Response from `getStickerSet`. (#838)

## [0.53.0] - 2018-04-01
### Notes
- [:ledger: View file changes][0.53.0] ∙ [:page_with_curl: DB migration script][0.53.0-sql-migration]
### Added
- Implemented new changes for Bot API 3.6 (streamable InputMediaVideo, connected website). (#799)
- `Telegram::getLastUpdateId()` method, returns ID of the last update that was processed. (#767)
- `Telegram::useGetUpdatesWithoutDatabase()` method, enables `Telegram::handleGetUpdates()` to run without a database. (#767)
### Changed
- Updated Travis to use Trusty containers (for HHVM) and add PHP 7.2 to the tests. (#739)
- Add debug log entry instead of throwing an exception for duplicate updates. (#765)
- `Telegram::handleGetUpdates()` can now work without a database connection (not enabled by default). (#767)
- Improved `/sendtochannel` and `/sendtoall` commands, using new message helpers. (#810)
### Fixed
- PHPCS fixes for updated CodeSniffer dependency. (#739)
- Send messages correctly via `/sendtochannel`. (#803)

## [0.52.0] - 2018-01-07
### Notes
- [:ledger: View file changes][0.52.0]
### Fixed
- Entity relations and wrong types for payments. (#731)
- Allow empty string for `switch_inline_query` and `switch_inline_query_current_chat` (InlineKeyboardButton). (#736)
- Fix empty date entry for User and Chat entities, using the current timestamp instead. (#738)

## [0.51.0] - 2017-12-05
### Notes
- [:ledger: View file changes][0.51.0] ∙ [:page_with_curl: DB migration script][0.51.0-sql-migration]
### Added
- Implemented new changes for Bot API 3.5 (InputMedia, MediaGroup). (#718)

## [0.50.0] - 2017-10-17
### Notes
- [:ledger: View file changes][0.50.0]
### Added
- Finish implementing payments, adding all missing type checks and docblock methods. (#647)
- Implemented new changes for Bot API 3.4 (Live Locations). (#675)
### Changed
- [:exclamation:][0.50.0-bc-messagegetcommand-return-value] `Message::getCommand()` returns `null` if not a command, instead of `false`. (#654)
### Fixed
- SQL update script for version 0.44.1-0.45.0.
- Issues found by Scrutinizer (Type hints and return values). (#654)
- Check inline keyboard button parameter value correctly. (#672)

## [0.49.0] - 2017-09-17
### Notes
- [:ledger: View file changes][0.49.0]
### Added
- Donation section and links in readme.
- Missing payment methods in `Request` class.
- Some helper methods for replying to commands and answering queries.
### Changed
- Updated and optimised all DB classes, removing a lot of bulky code.
### Fixed
- Ensure named SQL statement parameters are unique.
- Channel selection when using `DB::selectChats()`.

## [0.48.0] - 2017-08-26
### Notes
- [:ledger: View file changes][0.48.0] ∙ [:page_with_curl: DB migration script][0.48.0-sql-migration]
### Added
- New entities, methods, update types and inline keyboard button for Payments (Bot API 3.0).
- Add new methods, fields and objects for working with stickers (Bot API 3.2).
- New fields for Chat, User and Message objects (Bot API 3.3). `is_bot` added to `user` DB table.
### Changed
- [:exclamation:][0.48.0-bc-correct-printerror] Corrected `ServerResponse->printError` method to print by default and return by setting `$return` parameter.
- Ensure command names are handled as lower case.
### Fixed
- Correctly save `reply_to_message` to DB.

## [0.47.1] - 2017-08-06
### Notes
- [:ledger: View file changes][0.47.1]
### Added
- Linked version numbers in changelog for easy verification of code changes.
### Fixed
- Private-only commands work with edited messages now too.

## [0.47.0] - 2017-08-06 [YANKED]
### Notes
- [:ledger: View file changes][0.47.0]
### Changed
- Updated readme to latest state of 0.47.0.
### Fixed
- `Telegram::enableAdmin()` now handles duplicate additions properly.
- `Request::getMe()` failure doesn't break cron execution any more.
### Security
- [:exclamation:][0.47.0-bc-private-only-admin-commands] New command parameter `$private_only` to enforce usage in private chats only (set by default for Admin commands).

## [0.46.0] - 2017-07-15
### Notes
- [:ledger: View file changes][0.46.0]
### Added
- Callbacks can be added to be executed when callback queries are called.
- New Bot API 3.1 changes (#550).
- `/cleanup` command for admins, that cleans out old entries from the DB.
### Changed
- [:exclamation:][0.46.0-bc-request-class-refactor] Big refactor of the `Request` class, removing most custom method implementations.

## [0.45.0] - 2017-06-25
### Notes
- [:ledger: View file changes][0.45.0] ∙ [:page_with_curl: DB migration script][0.45.0-sql-migration]
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
### Notes
- [:ledger: View file changes][0.44.1]
### Fixed
- Erroneous exception when using webhook without a database connection.

## [0.44.0] - 2017-04-25
### Notes
- [:ledger: View file changes][0.44.0]
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
### Notes
- [:ledger: View file changes][0.43.0]
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
### Notes
- [:ledger: View file changes][0.42.0]
### Added
- Added `getBotId()` to directly access bot ID.
### Changed
- Rename `bot_name` to `bot_username` everywhere.
### Deprecated
- Deprecated `Telegram::getBotName()` (Use `Telegram::getBotUsername()` instead).
### Fixed
- Tests are more reliable now, using a properly formatted API key.

## [0.41.0] - 2017-03-25
### Notes
- [:ledger: View file changes][0.41.0]
### Added
- `$show_in_help` attribute for commands, to set if it should be displayed in the `/help` command.
- Link to new Telegram group: `https://telegram.me/PHP_Telegram_Bot_Support`
- Introduce change log.

## [0.40.1] - 2017-03-07
### Notes
- [:ledger: View file changes][0.40.1]
### Fixed
- Infinite message loop, caused by incorrect Entity variable.

## [0.40.0] - 2017-02-20
### Notes
- [:ledger: View file changes][0.40.0]
### Added
- Request limiter for incoming requests.
### Fixed
- Faulty formatting in logger.

## [0.39.0] - 2017-01-20
### Notes
- [:ledger: View file changes][0.39.0]
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
### Notes
- [:ledger: View file changes][0.38.1]
### Fixed
- Usage of self-signed certificates in conjunction with the new `allowed_updates` webhook parameter.

## [0.38.0] - 2016-12-25
### Notes
- [:ledger: View file changes][0.38.0]
### Added
- New `switch_inline_query_current_chat` option for inline keyboard.
- Support for `channel_post` and `edited_channel_post`.
- New alias `deleteWebhook` (for `unsetWebhook`).
### Changed
- Update WebhookInfo entity and `setWebhook` to allow passing of new arguments.

## [0.37.1] - 2016-12-24
### Notes
- [:ledger: View file changes][0.37.1]
### Fixed
- Keyboards that are built without using the KeyboardButton objects.
- Commands that are called via `/command@botname` by correctly passing them the bot name.

## [0.37.0] - 2016-12-13
### Notes
- [:ledger: View file changes][0.37.0]
### Changed
- Logging improvements to Botan integration.
### Deprecated
- Move `hideKeyboard` to `removeKeyboard`.

[0.61.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.60.0-0.61.0.sql
[0.61.0-bc-remove-monolog-from-core]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#remove-monolog-from-core
[0.58.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.57.0-0.58.0.sql
[0.58.0-bc-return-value-of-empty-entity-properties]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#return-value-of-empty-entity-properties
[0.58.0-bc-startcommand-is-now-a-usercommand]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#startcommand-is-now-a-usercommand
[0.57.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.56.0-0.57.0.sql
[0.55.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.54.1-0.55.0.sql
[0.55.0-bc-move-animation-out-of-games-namespace]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#move-animation-out-of-games-namespace
[0.54.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.53.0-0.54.0.sql
[0.54.0-bc-rename-constants]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#rename-constants
[0.53.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.52.0-0.53.0.sql
[0.51.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.50.0-0.51.0.sql
[0.50.0-bc-messagegetcommand-return-value]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#messagegetcommand-return-value
[0.48.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/master/utils/db-schema-update/0.47.1-0.48.0.sql
[0.48.0-bc-correct-printerror]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#correct-printerror
[0.47.0-bc-private-only-admin-commands]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#private-only-admin-commands
[0.46.0-bc-request-class-refactor]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#request-class-refactor
[0.46.0-sql-migration]: https://github.com/php-telegram-bot/core/tree/0.45.0/utils/db-schema-update/0.44.1-0.45.0.sql
[0.45.0-bc-remove-deprecated-methods]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#remove-deprecated-methods
[0.45.0-bc-chats-params-array]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#chats-params-array
[0.45.0-bc-up-download-directory]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#up-download-directory
[0.44.0-bc-update-content-type]: https://github.com/php-telegram-bot/core/wiki/Breaking-backwards-compatibility#update-getupdatecontent
[example-bot]: https://github.com/php-telegram-bot/example-bot
[PSR-3]: https://www.php-fig.org/psr/psr-3
[Tidelift]: https://tidelift.com/subscription/pkg/packagist-longman-telegram-bot?utm_source=packagist-longman-telegram-bot&utm_medium=referral&utm_campaign=changelog

[Unreleased]: https://github.com/php-telegram-bot/core/compare/master...develop
[0.61.1]: https://github.com/php-telegram-bot/core/compare/0.61.0...0.61.1
[0.61.0]: https://github.com/php-telegram-bot/core/compare/0.60.0...0.61.0
[0.60.0]: https://github.com/php-telegram-bot/core/compare/0.59.1...0.60.0
[0.59.1]: https://github.com/php-telegram-bot/core/compare/0.59.0...0.59.1
[0.59.0]: https://github.com/php-telegram-bot/core/compare/0.58.0...0.59.0
[0.58.0]: https://github.com/php-telegram-bot/core/compare/0.57.0...0.58.0
[0.57.0]: https://github.com/php-telegram-bot/core/compare/0.56.0...0.57.0
[0.56.0]: https://github.com/php-telegram-bot/core/compare/0.55.1...0.56.0
[0.55.1]: https://github.com/php-telegram-bot/core/compare/0.55.0...0.55.1
[0.55.0]: https://github.com/php-telegram-bot/core/compare/0.54.1...0.55.0
[0.54.1]: https://github.com/php-telegram-bot/core/compare/0.54.0...0.54.1
[0.54.0]: https://github.com/php-telegram-bot/core/compare/0.53.0...0.54.0
[0.53.0]: https://github.com/php-telegram-bot/core/compare/0.52.0...0.53.0
[0.52.0]: https://github.com/php-telegram-bot/core/compare/0.51.0...0.52.0
[0.51.0]: https://github.com/php-telegram-bot/core/compare/0.50.0...0.51.0
[0.50.0]: https://github.com/php-telegram-bot/core/compare/0.49.0...0.50.0
[0.49.0]: https://github.com/php-telegram-bot/core/compare/0.48.0...0.49.0
[0.48.0]: https://github.com/php-telegram-bot/core/compare/0.47.1...0.48.0
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
