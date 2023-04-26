ALTER TABLE `message`
    ADD COLUMN `has_media_spoiler`            TINYINT(1) DEFAULT 0 COMMENT 'True, if the message media is covered by a spoiler animation' AFTER `caption`,
    ADD COLUMN `write_access_allowed`         TEXT       DEFAULT NULL COMMENT 'Service message: the user allowed the bot added to the attachment menu to write messages' AFTER `connected_website`,
    ADD COLUMN `forum_topic_edited`           TEXT       DEFAULT NULL COMMENT 'Service message: forum topic edited' AFTER `forum_topic_created`,
    ADD COLUMN `general_forum_topic_hidden`   TEXT       DEFAULT NULL COMMENT 'Service message: the General forum topic hidden' AFTER `forum_topic_reopened`,
    ADD COLUMN `general_forum_topic_unhidden` TEXT       DEFAULT NULL COMMENT 'Service message: the General forum topic unhidden' AFTER `general_forum_topic_hidden`,
    ADD COLUMN `user_shared`                  TEXT       DEFAULT NULL COMMENT 'Optional. Service message: a user was shared with the bot' AFTER `successful_payment`,
    ADD COLUMN `chat_shared`                  TEXT       DEFAULT NULL COMMENT 'Optional. Service message: a chat was shared with the bot' AFTER `user_shared`;
