SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `telegram_update`
DROP KEY `message_id`;

ALTER TABLE `telegram_update`
ADD KEY `message_id` (`message_id`),
ADD KEY `chat_message_id` (`chat_id`, `message_id`);

SET FOREIGN_KEY_CHECKS=1;
