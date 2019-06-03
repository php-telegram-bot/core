ALTER TABLE `message` ADD COLUMN `reply_markup` TEXT NULL COMMENT 'Inline keyboard attached to the message' AFTER `passport_data`;
