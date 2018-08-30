CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint COMMENT 'Unique user identifier',
  `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True if this user is a bot',
  `first_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User''s first name',
  `last_name` CHAR(255) DEFAULT NULL COMMENT 'User''s last name',
  `username` CHAR(191) DEFAULT NULL COMMENT 'User''s username',
  `language_code` CHAR(10) DEFAULT NULL COMMENT 'User''s system language',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',

  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chat` (
  `id` bigint COMMENT 'Unique user or chat identifier',
  `type` ENUM('private', 'group', 'supergroup', 'channel') NOT NULL COMMENT 'Chat type, either private, group, supergroup or channel',
  `title` CHAR(255) DEFAULT '' COMMENT 'Chat (group) title, is null if chat type is private',
  `username` CHAR(255) DEFAULT NULL COMMENT 'Username, for private chats, supergroups and channels if available',
  `all_members_are_administrators` tinyint(1) DEFAULT 0 COMMENT 'True if a all members of this group are admins',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  `old_id` bigint DEFAULT NULL COMMENT 'Unique chat identifier, this is filled when a group is converted to a supergroup',

  PRIMARY KEY (`id`),
  KEY `old_id` (`old_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `user_chat` (
  `user_id` bigint COMMENT 'Unique user identifier',
  `chat_id` bigint COMMENT 'Unique user or chat identifier',

  PRIMARY KEY (`user_id`, `chat_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `inline_query` (
  `id` bigint UNSIGNED COMMENT 'Unique identifier for this query',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `location` CHAR(255) NULL DEFAULT NULL COMMENT 'Location of the user',
  `query` TEXT NOT NULL COMMENT 'Text of the query',
  `offset` CHAR(255) NULL DEFAULT NULL COMMENT 'Offset of the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chosen_inline_result` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `result_id` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Identifier for this result',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `location` CHAR(255) NULL DEFAULT NULL COMMENT 'Location object, user''s location',
  `inline_message_id` CHAR(255) NULL DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `query` TEXT NOT NULL COMMENT 'The query that was used to obtain the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `message` (
  `chat_id` bigint COMMENT 'Unique chat identifier',
  `id` bigint UNSIGNED COMMENT 'Unique message identifier',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier, sender of the original message',
  `forward_from_chat` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier, chat the original message belongs to',
  `forward_from_message_id` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier of the original message in the channel',
  `forward_date` timestamp NULL DEFAULT NULL COMMENT 'date the original message was sent in timestamp format',
  `reply_to_chat` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `reply_to_message` bigint UNSIGNED DEFAULT NULL COMMENT 'Message that this message is reply to',
  `media_group_id` TEXT COMMENT 'The unique identifier of a media message group this message belongs to',
  `text` TEXT COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4',
  `entities` TEXT COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `audio` TEXT COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` TEXT COMMENT 'Document object. Message is a general file, information about the file',
  `animation` TEXT COMMENT 'Message is an animation, information about the animation',
  `game` TEXT COMMENT 'Game object. Message is a game, information about the game',
  `photo` TEXT COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` TEXT COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` TEXT COMMENT 'Video object. Message is a video, information about the video',
  `voice` TEXT COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `video_note` TEXT COMMENT 'VoiceNote Object. Message is a Video Note, information about the Video Note',
  `contact` TEXT COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` TEXT COMMENT 'Location object. Message is a shared location, information about the location',
  `venue` TEXT COMMENT 'Venue object. Message is a Venue, information about the Venue',
  `caption` TEXT COMMENT  'For message with caption, the actual UTF-8 text of the caption',
  `new_chat_members` TEXT COMMENT 'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)',
  `left_chat_member` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)',
  `new_chat_title` CHAR(255) DEFAULT NULL COMMENT 'A chat title was changed to this value',
  `new_chat_photo` TEXT COMMENT 'Array of PhotoSize objects. A chat photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the chat photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_to_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier',
  `migrate_from_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier',
  `pinned_message` TEXT NULL COMMENT 'Message object. Specified message was pinned',
  `connected_website` TEXT NULL COMMENT 'The domain name of the website on which the user has logged in.',
  `passport_data` TEXT NULL COMMENT 'Telegram Passport data',

  PRIMARY KEY (`chat_id`, `id`),
  KEY `user_id` (`user_id`),
  KEY `forward_from` (`forward_from`),
  KEY `forward_from_chat` (`forward_from_chat`),
  KEY `reply_to_chat` (`reply_to_chat`),
  KEY `reply_to_message` (`reply_to_message`),
  KEY `left_chat_member` (`left_chat_member`),
  KEY `migrate_from_chat_id` (`migrate_from_chat_id`),
  KEY `migrate_to_chat_id` (`migrate_to_chat_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  FOREIGN KEY (`forward_from_chat`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`reply_to_chat`, `reply_to_message`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  FOREIGN KEY (`left_chat_member`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `callback_query` (
  `id` bigint UNSIGNED COMMENT 'Unique identifier for this query',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `chat_id` bigint NULL COMMENT 'Unique chat identifier',
  `message_id` bigint UNSIGNED COMMENT 'Unique message identifier',
  `inline_message_id` CHAR(255) NULL DEFAULT NULL COMMENT 'Identifier of the message sent via the bot in inline mode, that originated the query',
  `data` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Data associated with the callback button',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `edited_message` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` bigint COMMENT 'Unique chat identifier',
  `message_id` bigint UNSIGNED COMMENT 'Unique message identifier',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `edit_date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was edited in timestamp format',
  `text` TEXT COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8',
  `entities` TEXT COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `caption` TEXT COMMENT  'For message with caption, the actual UTF-8 text of the caption',

  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `telegram_update` (
  `id` bigint UNSIGNED COMMENT 'Update''s unique identifier',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Unique inline query identifier',
  `chosen_inline_result_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Local chosen inline result identifier',
  `callback_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Unique callback query identifier',
  `edited_message_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Local edited message identifier',

  PRIMARY KEY (`id`),
  KEY `message_id` (`chat_id`, `message_id`),
  KEY `inline_query_id` (`inline_query_id`),
  KEY `chosen_inline_result_id` (`chosen_inline_result_id`),
  KEY `callback_query_id` (`callback_query_id`),
  KEY `edited_message_id` (`edited_message_id`),

  FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`inline_query_id`) REFERENCES `inline_query` (`id`),
  FOREIGN KEY (`chosen_inline_result_id`) REFERENCES `chosen_inline_result` (`id`),
  FOREIGN KEY (`callback_query_id`) REFERENCES `callback_query` (`id`),
  FOREIGN KEY (`edited_message_id`) REFERENCES `edited_message` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `conversation` (
  `id` bigint(20) unsigned AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `user_id` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Unique user or chat identifier',
  `status` ENUM('active', 'cancelled', 'stopped') NOT NULL DEFAULT 'active' COMMENT 'Conversation state',
  `command` varchar(160) DEFAULT '' COMMENT 'Default command to execute',
  `notes` text DEFAULT NULL COMMENT 'Data stored from command',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `status` (`status`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `botan_shortener` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `user_id` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier',
  `url` text NOT NULL COMMENT 'Original URL',
  `short_url` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Shortened URL',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `request_limiter` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` char(255) NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `inline_message_id` char(255) NULL DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `method` char(255) DEFAULT NULL COMMENT 'Request method',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
