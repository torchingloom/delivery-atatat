
USE delivery;

INSERT INTO `delivery_template`
	(`name`, `subject`, `from`, `body_plain`, `body_html`)
VALUES 
	('Вышли стошку баксов', 'Тема такая - вышли стошку баксов', 'noreply@snob.ru', 'Hello %first_name%?\nВышли стошку баксов, брат!', '<p><b>Hello %first_name%?</b></p><p>Вышли стошку баксов, брат!</p>'),
	('Проврка', 'Проверка свзяи', 'nobody@snob.ru', 'Hello %first_name%!\nПроверка свзяи!', '<p><b>Hello %first_name%?</b></p><p>Проверка свзяи!</p>')
;

INSERT INTO `delivery_user_group`
	(`name`, `algo`)
VALUES 
	('Все действующие', 'all_subscribers__expiration_date_bigger_now'),
	('Все действующие ЧК', 'all_snobs__expiration_date_bigger_now')
;