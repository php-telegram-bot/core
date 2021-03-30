# Invite Links
CREATE TABLE IF NOT EXISTS `chat_invite_link` (
    `id` BIGINT UNSIGNED COMMENT 'Unique identifier for this entry',
    `invite_link` VARCHAR(2083) CHARACTER SET 'ascii' COLLATE 'ascii_general_ci' NOT NULL COMMENT 'The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”',
    # IE7 has a 2083 character limit for HTTP GET operations: http://support.microsoft.com/kb/208427
    `creator_id` BIGINT NOT NULL COMMENT 'Creator of the link',
    `is_primary` BOOLEAN NOT NULL COMMENT 'True, if the link is primary',
    `is_revoked` BOOLEAN NOT NULL COMMENT 'True, if the link is revoked',
    `expire_date` TIMESTAMP NULL COMMENT 'Point in time (Unix TIMESTAMP) when the link will expire or has been expired',
    `member_limit` MEDIUMINT UNSIGNED NULL COMMENT 'Maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999',

    PRIMARY KEY (`id`),

    FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

# New update type
CREATE TABLE IF NOT EXISTS `chat_member_updated` (
    `id` BIGINT UNSIGNED COMMENT 'Unique identifier for this entry',
    `chat_id` BIGINT NOT NULL COMMENT 'Chat the user belongs to',
    `user_id` BIGINT NOT NULL COMMENT 'Performer of the action, which resulted in the change',
    `date` TIMESTAMP NOT NULL COMMENT 'Date the change was done in Unix time',
    `old_chat_member` TEXT NOT NULL COMMENT 'Previous information about the chat member',
    `new_chat_member` TEXT NOT NULL COMMENT 'New information about the chat member',
    `chat_invite_link_id` BIGINT UNSIGNED NULL COMMENT 'Chat invite link, which was used by the user to join the chat; for joining by invite link events only',

    PRIMARY KEY (`id`),

    FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
    FOREIGN KEY (`chat_invite_link_id`) REFERENCES `chat_invite_link` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

# Updates about member status changes in chats
ALTER TABLE `telegram_update` ADD COLUMN `my_chat_member_update_id` BIGINT UNSIGNED NULL COMMENT 'The bot''s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`my_chat_member_update_id`) REFERENCES `chat_member_updated` (`id`);
ALTER TABLE `telegram_update` ADD COLUMN `chat_member_update_id` BIGINT UNSIGNED NULL COMMENT 'A chat member''s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_member_update_id`) REFERENCES `chat_member_updated` (`id`);

# New service messages
ALTER TABLE `message` ADD COLUMN `message_auto_delete_timer_changed` TEXT COMMENT 'MessageAutoDeleteTimerChanged object. Message is a service message: auto-delete timer settings changed in the chat' AFTER `channel_chat_created`;
ALTER TABLE `message` ADD COLUMN `voice_chat_started` TEXT COMMENT 'VoiceChatStarted object. Message is a service message: voice chat started' AFTER `proximity_alert_triggered`;
ALTER TABLE `message` ADD COLUMN `voice_chat_ended` TEXT COMMENT 'VoiceChatEnded object. Message is a service message: voice chat ended' AFTER `voice_chat_started`;
ALTER TABLE `message` ADD COLUMN `voice_chat_participants_invited` TEXT COMMENT 'VoiceChatParticipantsInvited object. Message is a service message: new participants invited to a voice chat' AFTER `voice_chat_ended`;
