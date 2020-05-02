ALTER TABLE `poll` ADD COLUMN `explanation` varchar(255) DEFAULT NULL COMMENT 'Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters' AFTER `correct_option_id`;
ALTER TABLE `poll` ADD COLUMN `explanation_entities` text DEFAULT NULL COMMENT 'Special entities like usernames, URLs, bot commands, etc. that appear in the explanation' AFTER `explanation`;
ALTER TABLE `poll` ADD COLUMN `open_period` int UNSIGNED DEFAULT NULL COMMENT 'Amount of time in seconds the poll will be active after creation' AFTER `explanation_entities`;
ALTER TABLE `poll` ADD COLUMN `close_date` timestamp NULL DEFAULT NULL COMMENT 'Point in time (Unix timestamp) when the poll will be automatically closed' AFTER `open_period`;

ALTER TABLE `poll_answer` DROP PRIMARY KEY, ADD PRIMARY KEY (`poll_id`, `user_id`);
