-- Replace the `message_id` key (`chat_id`, `message_id`) of `telegram_update` with the keys
-- `message_id` (`message_id`) and `chat_message_id` (`chat_id`, `message_id`).
-- MySQL refuses to drop a key that backs a foreign key, even with FOREIGN_KEY_CHECKS=0, so the old key is dropped
-- in the same statement that adds `chat_message_id`, which then backs the foreign key on (`chat_id`, `message_id`).
-- Each part runs only while it is needed, so the file can be run again after an attempt that stopped.
SET FOREIGN_KEY_CHECKS=0;

SET @message_id_key = (
    SELECT GROUP_CONCAT(`COLUMN_NAME` ORDER BY `SEQ_IN_INDEX`)
    FROM `information_schema`.`STATISTICS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'telegram_update'
      AND `INDEX_NAME` = 'message_id'
);
SET @chat_message_id_key = (
    SELECT COUNT(*)
    FROM `information_schema`.`STATISTICS`
    WHERE `TABLE_SCHEMA` = DATABASE()
      AND `TABLE_NAME` = 'telegram_update'
      AND `INDEX_NAME` = 'chat_message_id'
);
SET @message_id_keys_sql = CONCAT_WS(
    ', ',
    IF(@message_id_key IS NOT NULL AND @message_id_key <> 'message_id', 'DROP KEY `message_id`', NULL),
    IF(@message_id_key IS NULL OR @message_id_key <> 'message_id', 'ADD KEY `message_id` (`message_id`)', NULL),
    IF(@chat_message_id_key = 0, 'ADD KEY `chat_message_id` (`chat_id`, `message_id`)', NULL)
);
SET @message_id_keys_sql = IF(
    @message_id_keys_sql = '',
    'DO 0',
    CONCAT('ALTER TABLE `telegram_update` ', @message_id_keys_sql)
);
PREPARE message_id_keys_stmt FROM @message_id_keys_sql;
EXECUTE message_id_keys_stmt;
DEALLOCATE PREPARE message_id_keys_stmt;

SET FOREIGN_KEY_CHECKS=1;
