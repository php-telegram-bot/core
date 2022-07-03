ALTER TABLE `user` ADD COLUMN `is_premium` tinyint(1) DEFAULT 0 COMMENT 'True, if this user is a Telegram Premium user' AFTER `language_code`;
ALTER TABLE `user` ADD COLUMN `added_to_attachment_menu` tinyint(1) DEFAULT 0 COMMENT 'True, if this user added the bot to the attachment menu' AFTER `is_premium`;
