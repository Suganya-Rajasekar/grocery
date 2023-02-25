-- Saranya Feb 2023
ALTER TABLE `categories` ADD `res_id` INT NOT NULL AFTER `type`, ADD `visibility_mode` ENUM('on','off') NOT NULL DEFAULT 'off' AFTER `res_id`;

-- Suganya Feb 25
ALTER TABLE `cuisines` ADD `root_id` INT NOT NULL DEFAULT '0' AFTER `id`;
