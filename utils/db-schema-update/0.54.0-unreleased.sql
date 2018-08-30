ALTER TABLE `message` ADD COLUMN `animation` TEXT NULL COMMENT 'Message is an animation, information about the animation' AFTER `document`;
ALTER TABLE `message` ADD COLUMN `passport_data` TEXT NULL COMMENT 'Telegram Passport data' AFTER `connected_website`;
