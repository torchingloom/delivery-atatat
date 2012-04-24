
DROP TABLE IF EXISTS delivery_template;
DROP TABLE IF EXISTS delivery_user_to_group;
DROP TABLE IF EXISTS delivery_user_group;
DROP TABLE IF EXISTS delivery_user_to_task;
DROP TABLE IF EXISTS delivery_task;
DROP TABLE IF EXISTS delivery_user;


CREATE TABLE delivery_template
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(100) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `from` (`from`)s
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_task
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(500) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	`when_start` TIMESTAMP NULL,
	`status` ENUM('sheduled', 'executed', 'completed') DEFAULT 'sheduled',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `status` (`status`),
	KEY `when_start` (`when_start`),
	KEY `status_n_when_start` (`status`, `when_start`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_user
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` MEDIUMINT(8),
	`email` VARCHAR(200) NOT NULL,
	`first_name` VARCHAR(200),
	`last_name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY `email` (`email`),
	PRIMARY KEY (`id`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;



CREATE TABLE delivery_user_group
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_user_to_group
(
	`group_id` INT(11) UNSIGNED NOT NULL,
	`user_id` INT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`group_id`, `user_id`),
	CONSTRAINT `fk_group_id` FOREIGN KEY (`group_id`) REFERENCES `delivery_user_group` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk__user_id` FOREIGN KEY (`user_id`) REFERENCES `delivery_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `group_id` (`group_id`),
	KEY `user_id` (`user_id`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_user_to_task
(
	`task_id` INT(11) UNSIGNED NOT NULL,
	`user_id` INT(11) UNSIGNED NOT NULL,
	`when_send` TIMESTAMP NULL,
	PRIMARY KEY (`task_id`, `user_id`),
	CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `delivery_task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `delivery_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `task_id` (`task_id`),
	KEY `user_id` (`user_id`),
	KEY `when_send` (`when_send`),
	KEY `task_id_n_when_send` (`task_id`, `when_send`),
	KEY `user_id_n_when_send` (`user_id`, `when_send`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8;
;
