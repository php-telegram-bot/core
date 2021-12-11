CREATE TABLE IF NOT EXISTS `chat_join_request` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
    `chat_id` BIGINT NOT NULL COMMENT 'Chat to which the request was sent',
    `user_id` BIGINT NOT NULL COMMENT 'User that sent the join request',
    `date` TIMESTAMP NOT NULL COMMENT 'Date the request was sent in Unix time',
    `bio` TEXT NOT NULL COMMENT 'Optional. Bio of the user',
    `invite_link` TEXT NULL COMMENT 'Optional. Chat invite link that was used by the user to send the join request',
    `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

    PRIMARY KEY (`id`),

    FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `telegram_update` ADD COLUMN `chat_join_request_id` BIGINT UNSIGNED NULL COMMENT 'A request to join the chat has been sent';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_join_request_id`) REFERENCES `chat_join_request` (`id`);
