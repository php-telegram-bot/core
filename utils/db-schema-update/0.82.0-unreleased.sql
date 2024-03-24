CREATE TABLE IF NOT EXISTS `message_reaction` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint COMMENT 'The chat containing the message the user reacted to',
  `message_id` bigint COMMENT 'Unique identifier of the message inside the chat',
  `user_id` bigint NULL COMMENT 'Optional. The user that changed the reaction, if the user isn''t anonymous',
  `actor_chat_id` bigint NULL COMMENT 'Optional. The chat on behalf of which the reaction was changed, if the user is anonymous',
  `old_reaction` TEXT NOT NULL COMMENT 'Previous list of reaction types that were set by the user',
  `new_reaction` TEXT NOT NULL COMMENT 'New list of reaction types that have been set by the user',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `user_id` (`user_id`),
  KEY `actor_chat_id` (`actor_chat_id`),

  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`actor_chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `message_reaction_count` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint COMMENT 'The chat containing the message',
  `message_id` bigint COMMENT 'Unique message identifier inside the chat',
  `reactions` TEXT NOT NULL COMMENT 'List of reactions that are present on the message',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),

  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `message`
    ADD COLUMN `external_reply`       TEXT NULL DEFAULT NULL COMMENT 'Optional. Information about the message that is being replied to, which may come from another chat or forum topic' AFTER `reply_to_message`,
    ADD COLUMN `link_preview_options` TEXT NULL DEFAULT NULL COMMENT 'Optional. Options used for link preview generation for the message, if it is a text message and link preview options were changed' AFTER `via_bot`,
    RENAME COLUMN `user_shared` TO `users_shared`;

ALTER TABLE `telegram_update`
    ADD COLUMN `message_reaction_id`       bigint UNSIGNED DEFAULT NULL COMMENT 'A reaction to a message was changed by a user' AFTER `edited_channel_post_id`,
    ADD COLUMN `message_reaction_count_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Reactions to a message with anonymous reactions were changed' AFTER `message_reaction_id`;
