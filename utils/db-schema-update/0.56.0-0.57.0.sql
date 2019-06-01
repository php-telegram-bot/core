ALTER TABLE `chat` ADD COLUMN `first_name` CHAR(255) DEFAULT NULL COMMENT 'First name of the other party in a private chat' AFTER `username`;
ALTER TABLE `chat` ADD COLUMN `last_name` CHAR(255) DEFAULT NULL COMMENT 'Last name of the other party in a private chat' AFTER `first_name`;
ALTER TABLE `message` ADD COLUMN `forward_signature` TEXT NULL DEFAULT NULL COMMENT 'For messages forwarded from channels, signature of the post author if present' AFTER `forward_from_message_id`;
ALTER TABLE `message` ADD COLUMN `forward_sender_name` TEXT NULL DEFAULT NULL COMMENT 'Sender''s name for messages forwarded from users who disallow adding a link to their account in forwarded messages' AFTER `forward_signature`;
ALTER TABLE `message` ADD COLUMN `edit_date` bigint UNSIGNED DEFAULT NULL COMMENT 'Date the message was last edited in Unix time' AFTER `reply_to_message`;
ALTER TABLE `message` ADD COLUMN `author_signature` TEXT COMMENT 'Signature of the post author for messages in channels' AFTER `media_group_id`;
ALTER TABLE `message` ADD COLUMN `caption_entities` TEXT COMMENT 'For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption';
ALTER TABLE `message` ADD COLUMN `poll` TEXT COMMENT 'Poll object. Message is a native poll, information about the poll' AFTER `venue`;
ALTER TABLE `message` ADD COLUMN `invoice` TEXT NULL COMMENT 'Message is an invoice for a payment, information about the invoice' AFTER `pinned_message`;
ALTER TABLE `message` ADD COLUMN `successful_payment` TEXT NULL COMMENT 'Message is a service message about a successful payment, information about the payment' AFTER `invoice`;
ALTER TABLE `callback_query` ADD COLUMN `chat_instance` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent' AFTER `inline_message_id`;
ALTER TABLE `callback_query` ADD COLUMN `game_short_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Short name of a Game to be returned, serves as the unique identifier for the game' AFTER `data`;

CREATE TABLE IF NOT EXISTS `shipping_query` (
  `id` bigint UNSIGNED COMMENT 'Unique query identifier',
  `user_id` bigint COMMENT 'User who sent the query',
  `invoice_payload` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Bot specified invoice payload',
  `shipping_address` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User specified shipping address',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `pre_checkout_query` (
  `id` bigint UNSIGNED COMMENT 'Unique query identifier',
  `user_id` bigint COMMENT 'User who sent the query',
  `currency` CHAR(3) COMMENT 'Three-letter ISO 4217 currency code',
  `total_amount` bigint COMMENT 'Total price in the smallest units of the currency',
  `invoice_payload` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Bot specified invoice payload',
  `shipping_option_id` CHAR(255) NULL COMMENT 'Identifier of the shipping option chosen by the user',
  `order_info` TEXT NULL COMMENT 'Order info provided by the user',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `poll` (
  `id` bigint UNSIGNED COMMENT 'Unique poll identifier',
  `question` char(255) NOT NULL COMMENT 'Poll question',
  `options` text NOT NULL COMMENT 'List of poll options',
  `is_closed` tinyint(1) DEFAULT 0 COMMENT 'True, if the poll is closed',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `telegram_update` ADD COLUMN `channel_post_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming channel post of any kind - text, photo, sticker, etc.';
ALTER TABLE `telegram_update` ADD COLUMN `edited_channel_post_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New version of a channel post that is known to the bot and was edited';
ALTER TABLE `telegram_update` ADD COLUMN `shipping_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming shipping query. Only for invoices with flexible price';
ALTER TABLE `telegram_update` ADD COLUMN `pre_checkout_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming pre-checkout query. Contains full information about checkout';
ALTER TABLE `telegram_update` ADD COLUMN `poll_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New poll state. Bots receive only updates about polls, which are sent or stopped by the bot';

ALTER TABLE `telegram_update` ADD KEY `channel_post_id` (`channel_post_id`);
ALTER TABLE `telegram_update` ADD KEY `edited_channel_post_id` (`edited_channel_post_id`);
ALTER TABLE `telegram_update` ADD KEY `shipping_query_id` (`shipping_query_id`);
ALTER TABLE `telegram_update` ADD KEY `pre_checkout_query_id` (`pre_checkout_query_id`);
ALTER TABLE `telegram_update` ADD KEY `poll_id` (`poll_id`);

ALTER TABLE `telegram_update` ADD FOREIGN KEY (`chat_id`, `channel_post_id`) REFERENCES `message` (`chat_id`, `id`);
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`edited_channel_post_id`) REFERENCES `edited_message` (`id`);
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`shipping_query_id`) REFERENCES `shipping_query` (`id`);
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`pre_checkout_query_id`) REFERENCES `pre_checkout_query` (`id`);
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`);
