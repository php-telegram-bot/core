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

CREATE TABLE IF NOT EXISTS `chat_boost_updated` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint COMMENT 'Chat which was boosted',
  `boost` TEXT NOT NULL COMMENT 'Information about the chat boost',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),

  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chat_boost_removed` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint COMMENT 'Chat which was boosted',
  `boost_id` varchar(200) NOT NULL COMMENT 'Unique identifier of the boost',
  `remove_date` timestamp NOT NULL COMMENT 'Point in time (Unix timestamp) when the boost was removed',
  `source` TEXT NOT NULL COMMENT 'Source of the removed boost',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),

  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `message`
    ADD COLUMN `external_reply`       TEXT NULL DEFAULT NULL COMMENT 'Optional. Information about the message that is being replied to, which may come from another chat or forum topic' AFTER `reply_to_message`,
    ADD COLUMN `link_preview_options` TEXT NULL DEFAULT NULL COMMENT 'Optional. Options used for link preview generation for the message, if it is a text message and link preview options were changed' AFTER `via_bot`,
    CHANGE COLUMN `user_shared` `users_shared` TEXT,
    ADD COLUMN `boost_added` TEXT NULL COMMENT 'Service message: user boosted the chat' AFTER `proximity_alert_triggered`,
    ADD COLUMN `quote` TEXT NULL DEFAULT NULL COMMENT 'Optional. For replies that quote part of the original message, the quoted part of the message' AFTER `external_reply`,
    ADD COLUMN `reply_to_story` TEXT NULL DEFAULT NULL COMMENT 'Optional. For replies to a story, the original story' AFTER `quote`,
    ADD COLUMN `sender_boost_count` bigint NULL COMMENT 'If the sender of the message boosted the chat, the number of boosts added by the user' AFTER `user_id`;

ALTER TABLE `telegram_update`
    ADD COLUMN `message_reaction_id`       bigint UNSIGNED DEFAULT NULL COMMENT 'A reaction to a message was changed by a user' AFTER `edited_channel_post_id`,
    ADD COLUMN `message_reaction_count_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Reactions to a message with anonymous reactions were changed' AFTER `message_reaction_id`;

ALTER TABLE `telegram_update` ADD COLUMN `chat_boost_updated_id` BIGINT UNSIGNED NULL COMMENT 'A chat boost was added or changed.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_boost_updated_id`) REFERENCES `chat_boost_updated` (`id`);

ALTER TABLE `telegram_update` ADD COLUMN `chat_boost_removed_id` BIGINT UNSIGNED NULL COMMENT 'A boost was removed from a chat.';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_boost_removed_id`) REFERENCES `chat_boost_removed` (`id`);
