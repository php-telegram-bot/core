ALTER TABLE `telegram_update`
DROP KEY `message_id`,
ADD KEY `message_id` (`message_id`),
ADD KEY `chat_message_id` (`chat_id`, `message_id`);
