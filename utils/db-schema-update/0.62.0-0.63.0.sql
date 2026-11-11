-- Each change below runs only while it is needed, so the file can be run again after an attempt that stopped.
-- On MySQL, earlier versions of this file stopped after adding the `poll` columns.
SET @poll_explanation = (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'poll'
      AND `COLUMN_NAME` = 'explanation'
);
SET @poll_explanation_sql = IF(
    @poll_explanation > 0,
    'DO 0',
    'ALTER TABLE `poll` ADD COLUMN `explanation` varchar(255) DEFAULT NULL COMMENT ''Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters'' AFTER `correct_option_id`'
);
PREPARE poll_explanation_stmt FROM @poll_explanation_sql;
EXECUTE poll_explanation_stmt;
DEALLOCATE PREPARE poll_explanation_stmt;

SET @poll_explanation_entities = (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'poll'
      AND `COLUMN_NAME` = 'explanation_entities'
);
SET @poll_explanation_entities_sql = IF(
    @poll_explanation_entities > 0,
    'DO 0',
    'ALTER TABLE `poll` ADD COLUMN `explanation_entities` text DEFAULT NULL COMMENT ''Special entities like usernames, URLs, bot commands, etc. that appear in the explanation'' AFTER `explanation`'
);
PREPARE poll_explanation_entities_stmt FROM @poll_explanation_entities_sql;
EXECUTE poll_explanation_entities_stmt;
DEALLOCATE PREPARE poll_explanation_entities_stmt;

SET @poll_open_period = (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'poll'
      AND `COLUMN_NAME` = 'open_period'
);
SET @poll_open_period_sql = IF(
    @poll_open_period > 0,
    'DO 0',
    'ALTER TABLE `poll` ADD COLUMN `open_period` int UNSIGNED DEFAULT NULL COMMENT ''Amount of time in seconds the poll will be active after creation'' AFTER `explanation_entities`'
);
PREPARE poll_open_period_stmt FROM @poll_open_period_sql;
EXECUTE poll_open_period_stmt;
DEALLOCATE PREPARE poll_open_period_stmt;

SET @poll_close_date = (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'poll'
      AND `COLUMN_NAME` = 'close_date'
);
SET @poll_close_date_sql = IF(
    @poll_close_date > 0,
    'DO 0',
    'ALTER TABLE `poll` ADD COLUMN `close_date` timestamp NULL DEFAULT NULL COMMENT ''Point in time (Unix timestamp) when the poll will be automatically closed'' AFTER `open_period`'
);
PREPARE poll_close_date_stmt FROM @poll_close_date_sql;
EXECUTE poll_close_date_stmt;
DEALLOCATE PREPARE poll_close_date_stmt;

-- Add `user_id` to the primary key of `poll_answer`.
-- MySQL refuses this while the foreign key of `telegram_update`.`poll_answer_poll_id` references `poll_answer`,
-- and refuses that foreign key afterwards, because `poll_id` alone is then no unique key. So on MySQL the foreign key
-- is dropped and not restored; 0.83.1-0.84.0.sql adds one that references `poll`.`id` instead.
-- MariaDB accepts both and keeps the foreign key until 0.83.1-0.84.0.sql replaces it.
SET @poll_answer_pk = (
    SELECT GROUP_CONCAT(`COLUMN_NAME` ORDER BY `SEQ_IN_INDEX`)
    FROM `information_schema`.`STATISTICS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'poll_answer'
      AND `INDEX_NAME` = 'PRIMARY'
);
SET @poll_answer_fk = (
    SELECT `CONSTRAINT_NAME`
    FROM `information_schema`.`KEY_COLUMN_USAGE`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'telegram_update'
      AND `COLUMN_NAME` = 'poll_answer_poll_id'
      AND `REFERENCED_TABLE_NAME` = 'poll_answer'
    LIMIT 1
);
SET @poll_answer_fk_sql = IF(
    @poll_answer_pk = 'poll_id,user_id' OR @poll_answer_fk IS NULL OR VERSION() LIKE '%MariaDB%',
    'DO 0',
    CONCAT('ALTER TABLE `telegram_update` DROP FOREIGN KEY `', @poll_answer_fk, '`')
);
PREPARE poll_answer_fk_stmt FROM @poll_answer_fk_sql;
EXECUTE poll_answer_fk_stmt;
DEALLOCATE PREPARE poll_answer_fk_stmt;

SET @poll_answer_pk_sql = IF(
    @poll_answer_pk = 'poll_id,user_id',
    'DO 0',
    'ALTER TABLE `poll_answer` DROP PRIMARY KEY, ADD PRIMARY KEY (`poll_id`, `user_id`)'
);
PREPARE poll_answer_pk_stmt FROM @poll_answer_pk_sql;
EXECUTE poll_answer_pk_stmt;
DEALLOCATE PREPARE poll_answer_pk_stmt;

SET @via_bot_column = (
    SELECT COUNT(*)
    FROM `information_schema`.`COLUMNS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `COLUMN_NAME` = 'via_bot'
);
SET @via_bot_key = (
    SELECT COUNT(*)
    FROM `information_schema`.`STATISTICS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `INDEX_NAME` = 'via_bot'
);
SET @via_bot_fk = (
    SELECT COUNT(*)
    FROM `information_schema`.`KEY_COLUMN_USAGE`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `COLUMN_NAME` = 'via_bot'
      AND `REFERENCED_TABLE_NAME` = 'user'
);
SET @via_bot_sql = CONCAT_WS(
    ', ',
    IF(@via_bot_column = 0, 'ADD COLUMN `via_bot` bigint NULL DEFAULT NULL COMMENT ''Optional. Bot through which the message was sent'' AFTER `reply_to_message`', NULL),
    IF(@via_bot_key = 0, 'ADD KEY `via_bot` (`via_bot`)', NULL),
    IF(@via_bot_fk = 0, 'ADD FOREIGN KEY (`via_bot`) REFERENCES `user` (`id`)', NULL)
);
SET @via_bot_sql = IF(
    @via_bot_sql = '',
    'DO 0',
    CONCAT('ALTER TABLE `message` ', @via_bot_sql)
);
PREPARE via_bot_stmt FROM @via_bot_sql;
EXECUTE via_bot_stmt;
DEALLOCATE PREPARE via_bot_stmt;

-- The 0.44.1 schema declares the foreign key of `message`.`forward_from` twice. Drop the second copy, the one with
-- the higher generated number (`message_ibfk_6`, or `6` on MariaDB 12), and an index carrying its name if there is one.
-- The constraint name is generated by the server, so look it up by column instead of assuming it.
-- This runs after `via_bot` is added, so the generated name of the `via_bot` foreign key stays as it was.
SET @forward_from_fks = (
    SELECT COUNT(*)
    FROM `information_schema`.`KEY_COLUMN_USAGE`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `COLUMN_NAME` = 'forward_from'
      AND `REFERENCED_TABLE_NAME` = 'user'
      AND `REFERENCED_COLUMN_NAME` = 'id'
);
SET @forward_from_fk = (
    SELECT `CONSTRAINT_NAME`
    FROM `information_schema`.`KEY_COLUMN_USAGE`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `COLUMN_NAME` = 'forward_from'
      AND `REFERENCED_TABLE_NAME` = 'user'
      AND `REFERENCED_COLUMN_NAME` = 'id'
    ORDER BY LENGTH(`CONSTRAINT_NAME`) DESC, `CONSTRAINT_NAME` DESC
    LIMIT 1
);
SET @forward_from_fk_index = (
    SELECT COUNT(*)
    FROM `information_schema`.`STATISTICS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'message'
      AND `INDEX_NAME` = @forward_from_fk
);
SET @forward_from_fk_sql = IF(
    @forward_from_fks < 2,
    'DO 0',
    CONCAT(
        'ALTER TABLE `message` DROP FOREIGN KEY `', @forward_from_fk, '`',
        IF(@forward_from_fk_index > 0, CONCAT(', DROP INDEX `', @forward_from_fk, '`'), '')
    )
);
PREPARE forward_from_fk_stmt FROM @forward_from_fk_sql;
EXECUTE forward_from_fk_stmt;
DEALLOCATE PREPARE forward_from_fk_stmt;
