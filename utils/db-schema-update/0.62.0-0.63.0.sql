ALTER TABLE `conversation` CHANGE `status` `status` ENUM('active','cancelled','stopped','paused') NOT NULL DEFAULT 'active' COMMENT 'Conversation state';
