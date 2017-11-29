ALTER TABLE `message` ADD COLUMN `media_group_id` TEXT COMMENT 'The unique identifier of a media message group this message belongs to' AFTER `reply_to_message`;
