ALTER TABLE `poll` ADD COLUMN `total_voter_count` int UNSIGNED COMMENT 'Total number of users that voted in the poll' AFTER `options`;
ALTER TABLE `poll` ADD COLUMN `is_anonymous` tinyint(1) DEFAULT 1 COMMENT 'True, if the poll is anonymous' AFTER `is_closed`;
ALTER TABLE `poll` ADD COLUMN `type` char(255) COMMENT 'Poll type, currently can be “regular” or “quiz”' AFTER `is_anonymous`;
ALTER TABLE `poll` ADD COLUMN `allows_multiple_answers` tinyint(1) DEFAULT 0 COMMENT 'True, if the poll allows multiple answers' AFTER `type`;
ALTER TABLE `poll` ADD COLUMN `correct_option_id` int UNSIGNED COMMENT '0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.' AFTER `allows_multiple_answers`;
ALTER TABLE `message` ADD COLUMN `dice` TEXT COMMENT 'Message is a dice with random value from 1 to 6' AFTER `poll`;

CREATE TABLE IF NOT EXISTS `poll_answer` (
  `poll_id` bigint UNSIGNED COMMENT 'Unique poll identifier',
  `user_id` bigint NOT NULL COMMENT 'The user, who changed the answer to the poll',
  `option_ids` text NOT NULL COMMENT '0-based identifiers of answer options, chosen by the user. May be empty if the user retracted their vote.',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Entry date creation',

  PRIMARY KEY (`poll_id`),
  FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

ALTER TABLE `telegram_update` ADD COLUMN `poll_answer_poll_id` bigint UNSIGNED DEFAULT NULL COMMENT 'A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.' AFTER `poll_id`;
ALTER TABLE `telegram_update` ADD KEY `poll_answer_poll_id` (`poll_answer_poll_id`);
ALTER TABLE `telegram_update` ADD FOREIGN KEY (`poll_answer_poll_id`) REFERENCES `poll_answer` (`poll_id`);
