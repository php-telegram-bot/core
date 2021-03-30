ALTER TABLE `message` MODIFY `edit_date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was last edited in Unix time';

CREATE TABLE IF NOT EXISTS `chat_member_updated` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
    `chat_id` BIGINT NOT NULL COMMENT 'Chat the user belongs to',
    `user_id` BIGINT NOT NULL COMMENT 'Performer of the action, which resulted in the change',
    `date` TIMESTAMP NOT NULL COMMENT 'Date the change was done in Unix time',
    `old_chat_member` TEXT NOT NULL COMMENT 'Previous information about the chat member',
    `new_chat_member` TEXT NOT NULL COMMENT 'New information about the chat member',
    `invite_link` TEXT NULL COMMENT 'Chat invite link, which was used by the user to join the chat; for joining by invite link events only',
    `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

    PRIMARY KEY (`id`),

    FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `telegram_update` ADD COLUMN `my_chat_member_updated_id` BIGINT UNSIGNED NULL COMMENT 'The bot''s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`my_chat_member_updated_id`) REFERENCES `chat_member_updated` (`id`);
ALTER TABLE `telegram_update` ADD COLUMN `chat_member_updated_id` BIGINT UNSIGNED NULL COMMENT 'A chat member''s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_member_updated_id`) REFERENCES `chat_member_updated` (`id`);

ALTER TABLE `message` ADD COLUMN `message_auto_delete_timer_changed` TEXT COMMENT 'MessageAutoDeleteTimerChanged object. Message is a service message: auto-delete timer settings changed in the chat' AFTER `channel_chat_created`;
ALTER TABLE `message` ADD COLUMN `voice_chat_started` TEXT COMMENT 'VoiceChatStarted object. Message is a service message: voice chat started' AFTER `proximity_alert_triggered`;
ALTER TABLE `message` ADD COLUMN `voice_chat_ended` TEXT COMMENT 'VoiceChatEnded object. Message is a service message: voice chat ended' AFTER `voice_chat_started`;
ALTER TABLE `message` ADD COLUMN `voice_chat_participants_invited` TEXT COMMENT 'VoiceChatParticipantsInvited object. Message is a service message: new participants invited to a voice chat' AFTER `voice_chat_ended`;
