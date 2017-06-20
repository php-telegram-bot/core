ALTER TABLE `message` ADD COLUMN `new_chat_members` TEXT COMMENT 'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)' AFTER `new_chat_member`;
UPDATE `message` SET `new_chat_members` = `new_chat_member`;
