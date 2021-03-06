
USE snob_delivery;

-- SET FOREIGN_KEY_CHECKS=0;


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
	KEY `from` (`from`)
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
	`type` ENUM('manual', 'auto_by_time') DEFAULT 'manual' COMMENT 'Это поле как бы говорит нам о том, что подписка будет запускаться вручную или же в какое-то время',
	`status` ENUM('sheduled', 'executed', 'completed') DEFAULT 'sheduled',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `type` (`type`),
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
	`email` VARCHAR(200) NOT NULL,
	`login` VARCHAR(100) NOT NULL,
	`sex` TINYINT(1) NOT NULL DEFAULT 0,
	`first_name` VARCHAR(200),
	`last_name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`snob_user_id` INT(11) NULL,
	`snob_person_type` ENUM ('partner', 'editor', 'snob', 'starting', 'basic', 'premium') NULL COMMENT '"partner" партнеры;
"starting|basic|premium" подписчик;
"snob" ЧК;
"editor" сотрудник;
',
	`city` VARCHAR (200) NULL,
	`country` VARCHAR (200) NULL,
	`subscribe_start_date` TIMESTAMP NULL,
	`subscribe_end_date` TIMESTAMP NULL,
	`is_paid` TINYINT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`),
	KEY `snob_user_id` (`snob_user_id`),
	KEY `snob_person_type` (`snob_person_type`),
	KEY `city` (`city`),
	KEY `country` (`country`),
	KEY `subscribe_start_date` (`subscribe_start_date`),
	KEY `subscribe_end_date` (`subscribe_end_date`),
	KEY `last_name_n_first_name` (`last_name`, `first_name`),
	KEY `last_name_n_first_name_n_email` (`last_name`, `first_name`, `email`)

)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;



CREATE TABLE delivery_user_group
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`algo` VARCHAR (100) NULL COMMENT 'Ну типа идентификатор стратегии для автозаполнения',
	`when_autofill` TIMESTAMP NULL,
	PRIMARY KEY (`id`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE delivery_user_to_group
(
	`group_id` INT(11) UNSIGNED NOT NULL,
	`user_id` INT(11) UNSIGNED NOT NULL,
	`status` ENUM ('0', '1') NOT NULL DEFAULT '1' COMMENT '0 - не получает писем. В ТЗ есть пункт, на счет того, что если пользователя удалили вручную, то автоматически он не должен попасть. Для этого это поле и есть',
	PRIMARY KEY (`group_id`, `user_id`),
	CONSTRAINT `fk_delivery_user_to_group__group_id` FOREIGN KEY (`group_id`) REFERENCES `delivery_user_group` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_delivery_user_to_group__user_id` FOREIGN KEY (`user_id`) REFERENCES `delivery_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `group_id` (`group_id`),
	KEY `user_id` (`user_id`),
	KEY `status` (`status`),
	KEY `group_id_n_status` (`group_id`, `status`)
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
	CONSTRAINT `fk_delivery_user_to_task__task_id` FOREIGN KEY (`task_id`) REFERENCES `delivery_task` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `fk_delivery_user_to_task__user_id` FOREIGN KEY (`user_id`) REFERENCES `delivery_user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `task_id` (`task_id`),
	KEY `user_id` (`user_id`),
	KEY `when_send` (`when_send`),
	KEY `task_id_n_when_send` (`task_id`, `when_send`),
	KEY `user_id_n_when_send` (`user_id`, `when_send`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;



-- SET FOREIGN_KEY_CHECKS=1;




INSERT INTO `delivery_template`
	(`name`, `subject`, `from`, `body_plain`, `body_html`)
VALUES 
	('Вышли стошку баксов', 'Тема такая - вышли стошку баксов', 'noreply@snob.ru', 'Hello %first_name%?\nВышли стошку баксов, брат!', '<p><b>Hello %first_name%?</b></p><p>Вышли стошку баксов, брат!</p>'),
	('Проврка', 'Проверка свзяи', 'nobody@snob.ru', 'Hello %first_name%!\nПроверка свзяи!', '<p><b>Hello %first_name%?</b></p><p>Проверка свзяи!</p>')
;

INSERT INTO `delivery_user_group`
	(`name`, `algo`)
VALUES 
	('Зарегистрированные на сайте', 'all_subscribers__expiration_date_bigger_now')
	,('Сотрудники', 'all_employees')
	,('Партнеры', 'all_partners')
	,('Участники проекта - действительные', 'all_subscribers__premium_and_expiration_date_bigger_now')
	,('Участники проекта - не действительные', 'all_subscribers__premium_and_expiration_date_smaller_now')
	,('Подписчики журнала - действительные', 'all_subscribers__starting_and_expiration_date_bigger_now')
	,('Подписчики журнала - не действительные', 'all_subscribers__starting_and_expiration_date_smaller_now')
	,('Все действующие ЧК', 'all_snobs__expiration_date_bigger_now')
	,('Все бывшие ЧК', 'all_snobs__expiration_date_smaller_now')
	,('Бывшие участники', 'all_subscribers__expiration_date_smaller_now')
	,('Гости', 'all_guests')

--	,('Все за все времена (ух, предчувствую тормоза...)', 'all_subscribers')
;
