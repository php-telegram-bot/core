CREATE TABLE IF NOT EXISTS `chat_join_request` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
    `chat_id` BIGINT NOT NULL COMMENT 'Chat to which the request was sent',
    `user_id` BIGINT NOT NULL COMMENT 'User that sent the join request',
    `date` TIMESTAMP NOT NULL COMMENT 'Date the request was sent in Unix time',
    `bio` TEXT NULL COMMENT 'Optional. Bio of the user',
    `invite_link` TEXT NULL COMMENT 'Optional. Chat invite link that was used by the user to send the join request',
    `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

    PRIMARY KEY (`id`),

    FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `telegram_update` ADD COLUMN `chat_join_request_id` BIGINT UNSIGNED NULL COMMENT 'A request to join the chat has been sent';
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_join_request_id`) REFERENCES `chat_join_request` (`id`);

ALTER TABLE `message` ADD COLUMN `is_automatic_forward` tinyint(1) DEFAULT 0 COMMENT 'True, if the message is a channel post that was automatically forwarded to the connected discussion group' AFTER `forward_date`;
ALTER TABLE `message` ADD COLUMN `has_protected_content` tinyint(1) DEFAULT 0 COMMENT 'True, if the message can''t be forwarded' AFTER `edit_date`;
