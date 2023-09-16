ALTER TABLE `message`
    ADD COLUMN `story` TEXT DEFAULT NULL COMMENT 'Story object. Message is a forwarded story' AFTER `sticker`;
