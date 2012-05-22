
USE snob_full;

ALTER TABLE person ADD KEY type_id_n_expire_date (`expiration_date`, `person_type_id`);
ALTER TABLE person ADD KEY type_id_n_email (`email`, `person_type_id`);
ALTER TABLE person ADD KEY type_id_n_email_n_mod_date (`email`, `person_type_id`, `modification_date`);
ALTER TABLE person ADD KEY type_id_n_email_n_mod_date_n_epire_date (`email`, `person_type_id`, `modification_date`, `expiration_date`);