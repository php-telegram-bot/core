ALTER TABLE `message` ADD COLUMN `sender_chat_id` bigint COMMENT 'Sender of the message, sent on behalf of a chat' AFTER `chat_id`;
ALTER TABLE `message` ADD COLUMN `proximity_alert_triggered` TEXT NULL COMMENT 'Service message. A user in the chat triggered another user''s proximity alert while sharing Live Location.' AFTER `passport_data`;
ALTER TABLE `poll` MODIFY `question` text NOT NULL COMMENT 'Poll question';
