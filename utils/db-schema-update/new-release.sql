ALTER TABLE `message`
    ADD COLUMN `is_topic_message` TINYINT(1) DEFAULT 0 COMMENT 'True, if the message is sent to a forum topic' AFTER `forward_date`,
    ADD COLUMN `message_thread_id` BIGINT(20) NULL DEFAULT NULL COMMENT 'Unique identifier of a message thread to which the message belongs; for supergroups only' AFTER `id`,
    ADD COLUMN `forum_topic_created` TEXT NULL DEFAULT NULL COMMENT 'Service message: forum topic created' AFTER `proximity_alert_triggered`,
    ADD COLUMN `forum_topic_closed` TEXT NULL DEFAULT NULL COMMENT 'Service message: forum topic closed' AFTER `forum_topic_created`,
    ADD COLUMN `forum_topic_reopened` TEXT NULL DEFAULT NULL COMMENT 'Service message: forum topic reopened' AFTER `forum_topic_closed`;

ALTER TABLE `chat`
    ADD COLUMN `is_forum` TINYINT(1) DEFAULT 0 COMMENT 'True, if the supergroup chat is a forum (has topics enabled)' AFTER `last_name`;
