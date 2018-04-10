ALTER TABLE `message` ADD COLUMN `connected_website` TEXT NULL COMMENT 'The domain name of the website on which the user has logged in.' AFTER `pinned_message`;
