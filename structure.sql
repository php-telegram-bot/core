CREATE TABLE `messages` (
  `update_id` bigint UNSIGNED COMMENT 'The update\'s unique identifier.',
  `message_id` bigint COMMENT 'Unique message identifier',
  `user_id` bigint COMMENT 'User identifier',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date the message was sent in timestamp format',
  `chat_id` bigint NOT NULL DEFAULT '0' COMMENT 'Chat identifier.',
  `forward_from` bigint NOT NULL DEFAULT '0' COMMENT 'User id. For forwarded messages, sender of the original message',
  `forward_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'For forwarded messages, date the original message was sent in Unix time',
  `reply_to_message` bigint COMMENT 'Message is a reply to another message already stored.',
  `text` LONGTEXT COMMENT 'For text messages, the actual UTF-8 text of the message',
  `audio` TEXT DEFAULT '' COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` TEXT DEFAULT '' COMMENT 'Document object. Message is a general file, information about the file',
  `photo` TEXT DEFAULT '' COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` TEXT DEFAULT '' COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` TEXT DEFAULT '' COMMENT 'Video object. Message is a video, information about the video',
  `voice` TEXT DEFAULT '' COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `caption` LONGTEXT COMMENT 'For message with caption, the actual UTF-8 text of the caption',
  `contact` TEXT DEFAULT '' COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` TEXT DEFAULT '' COMMENT 'Location object. Message is a shared location, information about the location',
  `new_chat_participant` bigint NOT NULL DEFAULT '0' COMMENT 'User id. A new member was added to the group, information about them (this member may be bot itself)',
  `left_chat_participant` bigint NOT NULL DEFAULT '0' COMMENT 'User id. A member was removed from the group, information about them (this member may be bot itself)',
  `new_chat_title` CHAR(255) DEFAULT '' COMMENT 'A group title was changed to this value',
  `new_chat_photo` TEXT DEFAULT '' COMMENT 'Array of PhotoSize objects. A group photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `migrate_from_chat_id` bigint NOT NULL DEFAULT '0' COMMENT 'Migrate from chat identifier.',
  `migrate_to_chat_id` bigint NOT NULL DEFAULT '0' COMMENT 'Migrate to chat identifier.',
  PRIMARY KEY (`update_id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `users` (
  `id` bigint NOT NULL DEFAULT '0' COMMENT 'Unique user identifier',
  `first_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User first name',
  `last_name` CHAR(255) DEFAULT '' COMMENT 'User last name',
  `username` CHAR(255) DEFAULT '' COMMENT 'User username',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `chats` (
  `id` bigint NOT NULL DEFAULT '0' COMMENT 'Unique user or chat identifier',
  `type` CHAR(10) DEFAULT '' COMMENT 'chat type private, group, supergroup or channel',
  `title` CHAR(255) DEFAULT '' COMMENT 'chat title null if case of single chat with the bot',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date creation',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Entry date update',
  `old_id` bigint NOT NULL DEFAULT '0' COMMENT 'Unique chat identifieri this is filled when a chat is converted to a superchat',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `users_chats` (
  `user_id` bigint NOT NULL DEFAULT '0' COMMENT 'Unique user identifier',
  `chat_id` bigint NOT NULL DEFAULT '0' COMMENT 'Unique user or chat identifier',
  PRIMARY KEY (`user_id`, `chat_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;




