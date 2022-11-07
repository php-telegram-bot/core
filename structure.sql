CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint COMMENT 'Unique identifier for this user or bot',
  `is_bot` tinyint(1) DEFAULT 0 COMMENT 'True, if this user is a bot',
  `first_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'User''s or bot''s first name',
  `last_name` CHAR(255) DEFAULT NULL COMMENT 'User''s or bot''s last name',
  `username` CHAR(191) DEFAULT NULL COMMENT 'User''s or bot''s username',
  `language_code` CHAR(10) DEFAULT NULL COMMENT 'IETF language tag of the user''s language',
  `is_premium` tinyint(1) DEFAULT 0 COMMENT 'True, if this user is a Telegram Premium user',
  `added_to_attachment_menu` tinyint(1) DEFAULT 0 COMMENT 'True, if this user added the bot to the attachment menu',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date update',

  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chat` (
  `id` bigint COMMENT 'Unique identifier for this chat',
  `type` ENUM('private', 'group', 'supergroup', 'channel') NOT NULL COMMENT 'Type of chat, can be either private, group, supergroup or channel',
  `title` CHAR(255) DEFAULT '' COMMENT 'Title, for supergroups, channels and group chats',
  `username` CHAR(255) DEFAULT NULL COMMENT 'Username, for private chats, supergroups and channels if available',
  `first_name` CHAR(255) DEFAULT NULL COMMENT 'First name of the other party in a private chat',
  `last_name` CHAR(255) DEFAULT NULL COMMENT 'Last name of the other party in a private chat',
  `is_forum` TINYINT(1) DEFAULT 0 COMMENT 'True, if the supergroup chat is a forum (has topics enabled)',
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
  `chat_type` CHAR(255) NULL DEFAULT NULL COMMENT 'Optional. Type of the chat, from which the inline query was sent.',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `chosen_inline_result` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `result_id` CHAR(255) NOT NULL DEFAULT '' COMMENT 'The unique identifier for the result that was chosen',
  `user_id` bigint NULL COMMENT 'The user that chose the result',
  `location` CHAR(255) NULL DEFAULT NULL COMMENT 'Sender location, only for bots that require user location',
  `inline_message_id` CHAR(255) NULL DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `query` TEXT NOT NULL COMMENT 'The query that was used to obtain the result',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `message` (
  `chat_id` bigint COMMENT 'Unique chat identifier',
  `sender_chat_id` bigint COMMENT 'Sender of the message, sent on behalf of a chat',
  `id` bigint UNSIGNED COMMENT 'Unique message identifier',
  `message_thread_id` bigint(20) DEFAULT NULL COMMENT 'Unique identifier of a message thread to which the message belongs; for supergroups only',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was sent in timestamp format',
  `forward_from` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier, sender of the original message',
  `forward_from_chat` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier, chat the original message belongs to',
  `forward_from_message_id` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier of the original message in the channel',
  `forward_signature` TEXT NULL DEFAULT NULL COMMENT 'For messages forwarded from channels, signature of the post author if present',
  `forward_sender_name` TEXT NULL DEFAULT NULL COMMENT 'Sender''s name for messages forwarded from users who disallow adding a link to their account in forwarded messages',
  `forward_date` timestamp NULL DEFAULT NULL COMMENT 'date the original message was sent in timestamp format',
  `is_topic_message` tinyint(1) DEFAULT 0 COMMENT 'True, if the message is sent to a forum topic',
  `is_automatic_forward` tinyint(1) DEFAULT 0 COMMENT 'True, if the message is a channel post that was automatically forwarded to the connected discussion group',
  `reply_to_chat` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `reply_to_message` bigint UNSIGNED DEFAULT NULL COMMENT 'Message that this message is reply to',
  `via_bot` bigint NULL DEFAULT NULL COMMENT 'Optional. Bot through which the message was sent',
  `edit_date` timestamp NULL DEFAULT NULL COMMENT 'Date the message was last edited in Unix time',
  `has_protected_content` tinyint(1) DEFAULT 0 COMMENT 'True, if the message can''t be forwarded',
  `media_group_id` TEXT COMMENT 'The unique identifier of a media message group this message belongs to',
  `author_signature` TEXT COMMENT 'Signature of the post author for messages in channels',
  `text` TEXT COMMENT 'For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4',
  `entities` TEXT COMMENT 'For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text',
  `caption_entities` TEXT COMMENT 'For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption',
  `audio` TEXT COMMENT 'Audio object. Message is an audio file, information about the file',
  `document` TEXT COMMENT 'Document object. Message is a general file, information about the file',
  `animation` TEXT COMMENT 'Message is an animation, information about the animation',
  `game` TEXT COMMENT 'Game object. Message is a game, information about the game',
  `photo` TEXT COMMENT 'Array of PhotoSize objects. Message is a photo, available sizes of the photo',
  `sticker` TEXT COMMENT 'Sticker object. Message is a sticker, information about the sticker',
  `video` TEXT COMMENT 'Video object. Message is a video, information about the video',
  `voice` TEXT COMMENT 'Voice Object. Message is a Voice, information about the Voice',
  `video_note` TEXT COMMENT 'VoiceNote Object. Message is a Video Note, information about the Video Note',
  `caption` TEXT COMMENT  'For message with caption, the actual UTF-8 text of the caption',
  `contact` TEXT COMMENT 'Contact object. Message is a shared contact, information about the contact',
  `location` TEXT COMMENT 'Location object. Message is a shared location, information about the location',
  `venue` TEXT COMMENT 'Venue object. Message is a Venue, information about the Venue',
  `poll` TEXT COMMENT 'Poll object. Message is a native poll, information about the poll',
  `dice` TEXT COMMENT 'Message is a dice with random value from 1 to 6',
  `new_chat_members` TEXT COMMENT 'List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)',
  `left_chat_member` bigint NULL DEFAULT NULL COMMENT 'Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)',
  `new_chat_title` CHAR(255) DEFAULT NULL COMMENT 'A chat title was changed to this value',
  `new_chat_photo` TEXT COMMENT 'Array of PhotoSize objects. A chat photo was change to this value',
  `delete_chat_photo` tinyint(1) DEFAULT 0 COMMENT 'Informs that the chat photo was deleted',
  `group_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the group has been created',
  `supergroup_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the supergroup has been created',
  `channel_chat_created` tinyint(1) DEFAULT 0 COMMENT 'Informs that the channel chat has been created',
  `message_auto_delete_timer_changed` TEXT COMMENT 'MessageAutoDeleteTimerChanged object. Message is a service message: auto-delete timer settings changed in the chat',
  `migrate_to_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier',
  `migrate_from_chat_id` bigint NULL DEFAULT NULL COMMENT 'Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier',
  `pinned_message` TEXT NULL COMMENT 'Message object. Specified message was pinned',
  `invoice` TEXT NULL COMMENT 'Message is an invoice for a payment, information about the invoice',
  `successful_payment` TEXT NULL COMMENT 'Message is a service message about a successful payment, information about the payment',
  `connected_website` TEXT NULL COMMENT 'The domain name of the website on which the user has logged in.',
  `passport_data` TEXT NULL COMMENT 'Telegram Passport data',
  `proximity_alert_triggered` TEXT NULL COMMENT 'Service message. A user in the chat triggered another user''s proximity alert while sharing Live Location.',
  `forum_topic_created` TEXT DEFAULT NULL COMMENT 'Service message: forum topic created',
  `forum_topic_closed` TEXT DEFAULT NULL COMMENT 'Service message: forum topic closed',
  `forum_topic_reopened` TEXT DEFAULT NULL COMMENT 'Service message: forum topic reopened',
  `video_chat_scheduled` TEXT COMMENT 'Service message: video chat scheduled',
  `video_chat_started` TEXT COMMENT 'Service message: video chat started',
  `video_chat_ended` TEXT COMMENT 'Service message: video chat ended',
  `video_chat_participants_invited` TEXT COMMENT 'Service message: new participants invited to a video chat',
  `web_app_data` TEXT COMMENT 'Service message: data sent by a Web App',
  `reply_markup` TEXT NULL COMMENT 'Inline keyboard attached to the message',

  PRIMARY KEY (`chat_id`, `id`),
  KEY `user_id` (`user_id`),
  KEY `forward_from` (`forward_from`),
  KEY `forward_from_chat` (`forward_from_chat`),
  KEY `reply_to_chat` (`reply_to_chat`),
  KEY `reply_to_message` (`reply_to_message`),
  KEY `via_bot` (`via_bot`),
  KEY `left_chat_member` (`left_chat_member`),
  KEY `migrate_from_chat_id` (`migrate_from_chat_id`),
  KEY `migrate_to_chat_id` (`migrate_to_chat_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`forward_from`) REFERENCES `user` (`id`),
  FOREIGN KEY (`forward_from_chat`) REFERENCES `chat` (`id`),
  FOREIGN KEY (`reply_to_chat`, `reply_to_message`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`via_bot`) REFERENCES `user` (`id`),
  FOREIGN KEY (`left_chat_member`) REFERENCES `user` (`id`)
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

CREATE TABLE IF NOT EXISTS `callback_query` (
  `id` bigint UNSIGNED COMMENT 'Unique identifier for this query',
  `user_id` bigint NULL COMMENT 'Unique user identifier',
  `chat_id` bigint NULL COMMENT 'Unique chat identifier',
  `message_id` bigint UNSIGNED COMMENT 'Unique message identifier',
  `inline_message_id` CHAR(255) NULL DEFAULT NULL COMMENT 'Identifier of the message sent via the bot in inline mode, that originated the query',
  `chat_instance` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent',
  `data` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Data associated with the callback button',
  `game_short_name` CHAR(255) NOT NULL DEFAULT '' COMMENT 'Short name of a Game to be returned, serves as the unique identifier for the game',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `chat_id` (`chat_id`),
  KEY `message_id` (`message_id`),

  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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
  `question` text NOT NULL COMMENT 'Poll question',
  `options` text NOT NULL COMMENT 'List of poll options',
  `total_voter_count` int UNSIGNED COMMENT 'Total number of users that voted in the poll',
  `is_closed` tinyint(1) DEFAULT 0 COMMENT 'True, if the poll is closed',
  `is_anonymous` tinyint(1) DEFAULT 1 COMMENT 'True, if the poll is anonymous',
  `type` char(255) COMMENT 'Poll type, currently can be “regular” or “quiz”',
  `allows_multiple_answers` tinyint(1) DEFAULT 0 COMMENT 'True, if the poll allows multiple answers',
  `correct_option_id` int UNSIGNED COMMENT '0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.',
  `explanation` varchar(255) DEFAULT NULL COMMENT 'Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters',
  `explanation_entities` text DEFAULT NULL COMMENT 'Special entities like usernames, URLs, bot commands, etc. that appear in the explanation',
  `open_period` int UNSIGNED DEFAULT NULL COMMENT 'Amount of time in seconds the poll will be active after creation',
  `close_date` timestamp NULL DEFAULT NULL COMMENT 'Point in time (Unix timestamp) when the poll will be automatically closed',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE IF NOT EXISTS `poll_answer` (
  `poll_id` bigint UNSIGNED COMMENT 'Unique poll identifier',
  `user_id` bigint NOT NULL COMMENT 'The user, who changed the answer to the poll',
  `option_ids` text NOT NULL COMMENT '0-based identifiers of answer options, chosen by the user. May be empty if the user retracted their vote.',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`poll_id`, `user_id`),
  FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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

CREATE TABLE IF NOT EXISTS `telegram_update` (
  `id` bigint UNSIGNED COMMENT 'Update''s unique identifier',
  `chat_id` bigint NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `message_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming message of any kind - text, photo, sticker, etc.',
  `edited_message_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New version of a message that is known to the bot and was edited',
  `channel_post_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming channel post of any kind - text, photo, sticker, etc.',
  `edited_channel_post_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New version of a channel post that is known to the bot and was edited',
  `inline_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming inline query',
  `chosen_inline_result_id` bigint UNSIGNED DEFAULT NULL COMMENT 'The result of an inline query that was chosen by a user and sent to their chat partner',
  `callback_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming callback query',
  `shipping_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming shipping query. Only for invoices with flexible price',
  `pre_checkout_query_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New incoming pre-checkout query. Contains full information about checkout',
  `poll_id` bigint UNSIGNED DEFAULT NULL COMMENT 'New poll state. Bots receive only updates about polls, which are sent or stopped by the bot',
  `poll_answer_poll_id` bigint UNSIGNED DEFAULT NULL COMMENT 'A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.',
  `my_chat_member_updated_id` BIGINT UNSIGNED NULL COMMENT 'The bot''s chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.',
  `chat_member_updated_id` BIGINT UNSIGNED NULL COMMENT 'A chat member''s status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify “chat_member” in the list of allowed_updates to receive these updates.',
  `chat_join_request_id` BIGINT UNSIGNED NULL COMMENT 'A request to join the chat has been sent',

  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `chat_message_id` (`chat_id`, `message_id`),
  KEY `edited_message_id` (`edited_message_id`),
  KEY `channel_post_id` (`channel_post_id`),
  KEY `edited_channel_post_id` (`edited_channel_post_id`),
  KEY `inline_query_id` (`inline_query_id`),
  KEY `chosen_inline_result_id` (`chosen_inline_result_id`),
  KEY `callback_query_id` (`callback_query_id`),
  KEY `shipping_query_id` (`shipping_query_id`),
  KEY `pre_checkout_query_id` (`pre_checkout_query_id`),
  KEY `poll_id` (`poll_id`),
  KEY `poll_answer_poll_id` (`poll_answer_poll_id`),
  KEY `my_chat_member_updated_id` (`my_chat_member_updated_id`),
  KEY `chat_member_updated_id` (`chat_member_updated_id`),
  KEY `chat_join_request_id` (`chat_join_request_id`),

  FOREIGN KEY (`chat_id`, `message_id`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`edited_message_id`) REFERENCES `edited_message` (`id`),
  FOREIGN KEY (`chat_id`, `channel_post_id`) REFERENCES `message` (`chat_id`, `id`),
  FOREIGN KEY (`edited_channel_post_id`) REFERENCES `edited_message` (`id`),
  FOREIGN KEY (`inline_query_id`) REFERENCES `inline_query` (`id`),
  FOREIGN KEY (`chosen_inline_result_id`) REFERENCES `chosen_inline_result` (`id`),
  FOREIGN KEY (`callback_query_id`) REFERENCES `callback_query` (`id`),
  FOREIGN KEY (`shipping_query_id`) REFERENCES `shipping_query` (`id`),
  FOREIGN KEY (`pre_checkout_query_id`) REFERENCES `pre_checkout_query` (`id`),
  FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`),
  FOREIGN KEY (`poll_answer_poll_id`) REFERENCES `poll_answer` (`poll_id`),
  FOREIGN KEY (`my_chat_member_updated_id`) REFERENCES `chat_member_updated` (`id`),
  FOREIGN KEY (`chat_member_updated_id`) REFERENCES `chat_member_updated` (`id`),
  FOREIGN KEY (`chat_join_request_id`) REFERENCES `chat_join_request` (`id`)
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

CREATE TABLE IF NOT EXISTS `request_limiter` (
  `id` bigint UNSIGNED AUTO_INCREMENT COMMENT 'Unique identifier for this entry',
  `chat_id` char(255) NULL DEFAULT NULL COMMENT 'Unique chat identifier',
  `inline_message_id` char(255) NULL DEFAULT NULL COMMENT 'Identifier of the sent inline message',
  `method` char(255) DEFAULT NULL COMMENT 'Request method',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
