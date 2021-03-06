
USE snob_delivery;

-- SET FOREIGN_KEY_CHECKS=0;


DROP TABLE IF EXISTS `delivery_template`;
DROP TABLE IF EXISTS `delivery_user_to_group`;
DROP TABLE IF EXISTS `delivery_user_group`;
DROP TABLE IF EXISTS `delivery_user_group_category`;
DROP TABLE IF EXISTS `delivery_user_to_task`;
DROP TABLE IF EXISTS `delivery_task`;
DROP TABLE IF EXISTS `delivery_user`;




CREATE TABLE `delivery_template`
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(100) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	`kind` ENUM('default', 'system') NOT NULL DEFAULT 'default',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `from` (`from`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;


CREATE TABLE `delivery_task`
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(2000) NOT NULL,
	`subject` VARCHAR(500) NOT NULL,
	`from` VARCHAR(500) NOT NULL,
	`body_plain` TEXT,
	`body_html` TEXT,
	`when_start` TIMESTAMP NULL,
	`type` ENUM('manual', 'auto_by_time') DEFAULT 'auto_by_time' COMMENT 'Это поле как бы говорит нам о том, что подписка будет запускаться вручную или же в какое-то время',
	`status` ENUM('scheduled', 'completed') DEFAULT 'scheduled',
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


CREATE TABLE `delivery_user`
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(200) NOT NULL,
	`login` VARCHAR(100) NOT NULL,
	`sex` TINYINT(1) NOT NULL DEFAULT 0,
	`first_name` VARCHAR(200) NOT NULL,
	`last_name` VARCHAR(200),
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`snob_user_id` INT(11) NULL,
	`snob_person_type` ENUM ('editor', 'snob', 'starting', 'basic', 'premium') NULL COMMENT '
"starting|basic|premium" подписчик;
"snob" ЧК;
"editor" сотрудник;
',
	`snob_person_partner` TINYINT(1),
	`city` VARCHAR (200) NULL,
	`country` VARCHAR (200) NULL,
	`subscribe_start_date` TIMESTAMP NULL,
	`subscribe_end_date` TIMESTAMP NULL,
	`is_paid` TINYINT(1) NOT NULL DEFAULT 0,
	`status` ENUM('pending', 'deleted', 'normal') NOT NULL DEFAULT 'normal',
	`activate_code` VARCHAR(32) NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`),
	KEY `snob_user_id` (`snob_user_id`),
	KEY `snob_person_type` (`snob_person_type`),
	KEY `snob_person_partner` (`snob_person_partner`),
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



CREATE TABLE `delivery_user_group_category`
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(200) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`)
)
	ENGINE=INNODB 
	DEFAULT CHARSET=utf8
;

CREATE TABLE `delivery_user_group`
(
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`category_id` INT(11) UNSIGNED NULL,
	`name` VARCHAR(200) NOT NULL,
	`when_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`algo` VARCHAR (100) NULL COMMENT 'Ну типа идентификатор стратегии для автозаполнения',
	`when_autofill` TIMESTAMP NULL,
	`autofill_order_position` INT(6) NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	CONSTRAINT `fk_delivery_user_group__category_id` FOREIGN KEY (`category_id`) REFERENCES `delivery_user_group_category` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	KEY `category_id` (`category_id`),
	KEY `algo` (`algo`),
	KEY `when_autofill_n_autofill_order_position` (`when_autofill`, `autofill_order_position`)
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
	(`name`, `subject`, `from`, `body_html`, `kind`)
VALUES 
	('Подписка - запрос подтверждения', 'Запрос подтверждение регистрации', 'snob@snob.ru', '<p>Hello, %first_name!</p><p>Кто-то, возможно что и вы, указал этот электронный адрес (%email) на snob.ru!</p><p>Если вы действительно хотите обратиться к свету, то перейдите по ссылке %site_url/confirm/%activate_code</p><p>Или прозябайте во тьме</p>', 'system'),
	('Подписка - информирование о подтверждении', 'Подтверждение регистрации', 'snob@snob.ru', '<p>Радостно приветствуем, %first_name!</p><p>Теперь вы не останетесь в неведении</p>', 'system')
;

INSERT INTO `delivery_user_group_category`
	(`name`)
VALUES
	('Зарегистрированные'), ('ЧК'), ('Участники'), ('Подписчики'), ('Другие'), ('Гости'), ('Пользовательские')
;

SET @autofill_order_position := 0;

INSERT INTO `delivery_user_group`
	(`name`, `algo`, `category_id`, `autofill_order_position`)
VALUES 

	('Сотрудники', 'all_employees', 5, @autofill_order_position := @autofill_order_position + 100)
	,('Партнеры', 'all_partners', 5, @autofill_order_position := @autofill_order_position + 100)
	,('Все действующие ЧК', 'all_snobs__expiration_date_bigger_now', 2, @autofill_order_position := @autofill_order_position + 100)
	,('Все бывшие ЧК', 'all_snobs__expiration_date_smaller_now', 2, @autofill_order_position := @autofill_order_position + 100)
	,('Участники проекта - действительные', 'all_subscribers__premium_and_expiration_date_bigger_now', 3, @autofill_order_position := @autofill_order_position + 100)
	,('Участники проекта - не действительные', 'all_subscribers__premium_and_expiration_date_smaller_now', 3, @autofill_order_position := @autofill_order_position + 100)
	,('Подписчики журнала - действительные', 'all_subscribers__starting_and_expiration_date_bigger_now', 4, @autofill_order_position := @autofill_order_position + 100)
	,('Подписчики журнала - не действительные', 'all_subscribers__starting_and_expiration_date_smaller_now', 4, @autofill_order_position := @autofill_order_position + 100)
	,('Гости', 'all_guests', 6, @autofill_order_position := @autofill_order_position + 100)
	,('Бывшие участники', 'all_subscribers__expiration_date_smaller_now', 1, @autofill_order_position := @autofill_order_position + 100)
	,('Зарегистрированные на сайте', 'all_subscribers__expiration_date_bigger_now', 1, @autofill_order_position := @autofill_order_position + 100)

--	,('Все за все времена (ух, предчувствую тормоза...)', 'all_subscribers', @autofill_order_position := @autofill_order_position + 100)
;
