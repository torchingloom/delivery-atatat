
USE snob;

SET @gid = (SELECT MAX(id) + 100 FROM person_settings_group_dict);

INSERT INTO person_settings_group_dict
SET
	id = @gid,
	title = 'Электронные письма от проекта',
	sort = @gid
;

SET @id = (SELECT MAX(id) + 100 FROM person_settings_dict);

INSERT INTO person_settings_dict 
SET 
	id = @id,
	name = 'all_from_delivery', 
	group_id = @gid, 
	title = 'Получать рассылку с информационными материалами от проекта «Сноб»', 
	kind = 'notice', 
	default_value = 1, 
	sort = 100, 
	show_in_settings_interface = 1
;
	
	
INSERT INTO person_settings_dict_notice
SET 
	person_settings_dict_id = @id,
	allowed_smail = 0,
	default_smail = 0,
	maychange_smail = 0,
	allowed_email = 1,
	default_email = 1,
	maychange_email = 1
;