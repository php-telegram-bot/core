ALTER TABLE `user` ADD COLUMN `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True if this user is a bot' AFTER `id`;
