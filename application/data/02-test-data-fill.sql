

INSERT INTO `delivery_template`
	(`name`, `subject`, `from`, `body_plain`, `body_html`)
VALUES 
	('Вышли стошку баксов', 'Тема такая - вышли стошку баксов', 'noreply@snob.ru', 'Hello %first_name%?\nВышли стошку баксов, брат!', '<p><b>Hello %first_name%?</b></p><p>Вышли стошку баксов, брат!</p>'),
	('Проврка', 'Проверка свзяи', 'nobody@snob.ru', 'Hello %first_name%!\nПроверка свзяи!', '<p><b>Hello %first_name%?</b></p><p>Проверка свзяи!</p>')
;