
USE snob_full;

ALTER TABLE person ADD KEY person_type_id_n_expiration_date (`expiration_date`, `person_type_id`);