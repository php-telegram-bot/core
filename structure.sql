CREATE TABLE IF NOT EXISTS  `user` (
  `id` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier',
  `first_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User first name',
  `last_name` CHAR(255) DEFAULT NULL COMMENT 'User last name',
  `username` CHAR(255) DEFAULT NULL COMMENT 'User username',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS  `chat` (
  `id` bigint NULL DEFAULT NULL COMMENT 'Unique user or chat identifier',
  `type` ENUM('private', 'group', 'supergroup', 'channel') NOT NULL COMMENT 'chat type private, group, supergroup or channel',
  `title` CHAR(255) DEFAULT '' COMMENT 'chat title null if case of single chat with the bot',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',
  `old_id` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifieri this is filled when a chat is converted to a superchat',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS  `user_chat` (
  `user_id` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Unique user or chat identifier',
  PRIMARY KEY (`user_id`, `chat_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `inline_query` (
  `id` bigint UNSIGNED NULL COMMENT 'Unique identifier for this query.',
  `user_id` bigint NULL COMMENT 'Sender',
  `query` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Text of the query',
  `offset` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Offset of the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),

   FOREIGN KEY (`user_id`)
   REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `chosen_inline_query` (
  `id` bigint UNSIGNED NULL AUTO_INCREMENT COMMENT 'Unique identifier for chosen query.',
  `result_id` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Id of the chosen result',
  `user_id` bigint NULL COMMENT 'Sender',
  `query` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Text of the query',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),

   FOREIGN KEY (`user_id`)
   REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS  `message` (
  `id` bigint UNSIGNED NULL COMMENT 'Unique message identifier',
  `user_id` bigint NULL COMMENT 'User identifier',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Chat identifier.',
  `date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint NULL DEFAULT NULL COMMENT 'User id. For forwarded messages, sender of the original message',
  `forward_date` timestamp NULL DEFAULT NULL COMMENT 'For forwarded messages, date the original message was sent in Unix time',
  `reply_to_message` bigint UNSIGNED DEFAULT NULL COMMENT 'Message is a reply to another message.',
  `text` TEXT DEFAULT NULL COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8',
  `audio` TEXT DEFAULT NULL COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` TEXT DEFAULT NULL COMMENT 'Document object. Message is a general file, information about the file',
  `photo` TEXT DEFAULT NULL COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` TEXT DEFAULT NULL COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` TEXT DEFAULT NULL COMMENT 'Video object. Message is a video, information about the video',
  `voice` TEXT DEFAULT NULL COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `caption` TEXT DEFAULT NULL COMMENT  'For message with caption, the actual UTF-8 text of the caption',
  `contact` TEXT DEFAULT NULL COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` TEXT DEFAULT NULL COMMENT 'Location object. Message is a shared location, information about the location',
  `new_chat_participant` bigint NULL DEFAULT NULL COMMENT 'User id. A new member was added to the group, information about them (this member may be bot itself)',
  `left_chat_participant` bigint NULL DEFAULT NULL COMMENT 'User id. A member was removed from the group, information about them (this member may be bot itself)',
  `new_chat_title` CHAR(255) DEFAULT NULL COMMENT 'A group title was changed to this value',
  `new_chat_photo` TEXT DEFAULT NULL COMMENT 'Array of PhotoSize objects. A group photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_from_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate from chat identifier.',
  `migrate_to_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate to chat identifier.',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `forward_from` (`forward_from`),
  KEY `reply_to_message` (`reply_to_message`),
  KEY `new_chat_participant` (`new_chat_participant`),
  KEY `left_chat_participant` (`left_chat_participant`),
  KEY `migrate_from_chat_id` (`migrate_from_chat_id`),
  KEY `migrate_to_chat_id` (`migrate_to_chat_id`),

  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`)
  REFERENCES `chat` (`id`),
  FOREIGN KEY (`forward_from`)
  REFERENCES `user` (`id`),
  FOREIGN KEY (`reply_to_message`)
  REFERENCES `message` (`id`),
  FOREIGN KEY (`forward_from`)
  REFERENCES `user` (`id`),
  FOREIGN KEY (`new_chat_participant`)
  REFERENCES `user` (`id`),
  FOREIGN KEY (`left_chat_participant`)
  REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE IF NOT EXISTS `telegram_update` (
  `id` bigint UNSIGNED NULL COMMENT 'The update\'s unique identifier.',
  `message_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Unique message identifier',
  `inline_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'The query unique identifier.',
  `chosen_inline_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'The chosen query unique identifier.',

  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `inline_query_id` (`inline_query_id`),
  KEY `chosen_inline_query_id` (`chosen_inline_query_id`),

  FOREIGN KEY (`message_id`)
  REFERENCES `message` (`id`),

  FOREIGN KEY (`inline_query_id`)
  REFERENCES `inline_query` (`id`),

  FOREIGN KEY (`chosen_inline_query_id`)
  REFERENCES `chosen_inline_query` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `conversation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Row unique id',
  `user_id` bigint NULL DEFAULT NULL COMMENT 'User id',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Telegram chat_id can be a the user id or the chat id ',
  `status` ENUM('active', 'cancelled', 'stopped') NOT NULL DEFAULT 'active' COMMENT 'active conversation is active, cancelled conversation has been truncated before end, stopped conversation has end',
  `command` varchar(160) DEFAULT '' COMMENT 'Default Command to execute',
  `data` varchar(1000) DEFAULT 'NULL' COMMENT 'Data stored from command',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `status` (`status`),

  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`)
  REFERENCES `chat` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
