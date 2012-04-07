
DROP TABLE IF EXISTS delivery_template;
DROP TABLE IF EXISTS delivery_user_to_group;
DROP TABLE IF EXISTS delivery_email_to_group;
DROP TABLE IF EXISTS delivery_user_group;

DROP TABLE IF EXISTS delivery_user_to_task;
DROP TABLE IF EXISTS delivery_email_to_task;
DROP TABLE IF EXISTS delivery_task;

CREATE TABLE delivery_user_group
(
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
	ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE delivery_user_to_group
(
	`group_id` INT(10) NOT NULL,
	`user_id` MEDIUMINT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`group_id`, `user_id`),
	CONSTRAINT `fk_delivery_user_to_group__group_id` FOREIGN KEY (`group_id`) REFERENCES `delivery_user_group` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_delivery_user_to_group__user_id` FOREIGN KEY (`user_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `group_id` (`group_id`),
	KEY `user_id` (`user_id`)
)
	ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE delivery_template
(
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(500) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	PRIMARY KEY (`id`)
)
	ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE delivery_task
(
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(500) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	`start_date` TIMESTAMP NULL,
	`status` ENUM('sheduled','sending','completed', 'pending') DEFAULT 'sheduled',

	PRIMARY KEY (`id`)
)
	ENGINE=InnoDB DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_user_to_task
(
	`task_id` INT(10) NOT NULL,
	`user_id` MEDIUMINT(11) UNSIGNED NOT NULL,
	PRIMARY KEY (`task_id`, `user_id`),
	CONSTRAINT `fk_delivery_user_to_task__group_id` FOREIGN KEY (`task_id`) REFERENCES `delivery_task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_delivery_user_to_task__user_id` FOREIGN KEY (`user_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `task_id` (`task_id`),
	KEY `user_id` (`user_id`)
)
	ENGINE=InnoDB DEFAULT CHARSET=utf8;
;




CREATE TABLE `delivery_email_to_task` (
  `task_id` INT(10) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`task_id`,`email`),
  KEY `task_id` (`task_id`),
  KEY `email` (`email`),
  CONSTRAINT `fk_delivery_user_to_task__task` FOREIGN KEY (`task_id`) REFERENCES `delivery_task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;



CREATE TABLE delivery_log
(
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`task_id` INT(10) NOT NULL,
	`user_id` MEDIUMINT(11) UNSIGNED NOT NULL,
	`user_email` VARCHAR(500) NOT NULL,
	`send_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`memo`  VARCHAR(500) NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `delivery_smtp` (
  `smtp_id` INT(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` VARCHAR(128) NOT NULL,
  `smtp_port` INT(11) NOT NULL,
  `smtp_user` VARCHAR(128) DEFAULT NULL,
  `smtp_pass` VARCHAR(128) DEFAULT NULL,
  `smtp_default_mail_tick` INT(11) NOT NULL,
  `smtp_status` ENUM('active','inactive','banned','deleted') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`smtp_id`)
) ENGINE=INNODB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `delivery_task_controlmark` (
  `id` INT(10) NOT NULL,
  `email_last_id` INT(11) DEFAULT NULL,
  `type` ENUM('mark','error') NOT NULL DEFAULT 'mark',
  PRIMARY KEY (`id`,`type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `subscribe_email` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(500) NOT NULL,
  `subscribe_date` TIMESTAMP NULL DEFAULT NULL,
  `subscribe_type` INT(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `delivery_email_to_group` (
  `group_id` INT(10) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`group_id`,`email`),

  KEY `group_id` (`group_id`),
  KEY `email` (`email`),

  CONSTRAINT `fk_delivery_email_to_group__group_id` FOREIGN KEY (`group_id`) REFERENCES `delivery_user_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=INNODB DEFAULT CHARSET=utf8;