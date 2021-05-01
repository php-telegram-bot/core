ALTER TABLE `message` ADD COLUMN `voice_chat_scheduled` TEXT COMMENT 'VoiceChatScheduled object. Message is a service message: voice chat scheduled' AFTER `proximity_alert_triggered`;
ALTER TABLE `inline_query` ADD COLUMN `chat_type` CHAR(255) NULL DEFAULT NULL COMMENT 'Optional. Type of the chat, from which the inline query was sent.' AFTER `offset`;
