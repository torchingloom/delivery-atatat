<?php

namespace DataSource\Mysql\DbCommand;

class SnobUserGetter extends DbCommand
{
	public function SnobUserGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'expiration_date' => null,
                'modification_date' => null,
                'person_type_id' => null,
                'subscribe_plan_name' => null,
                'is_partner' => null,
                '__FETCH__' => array('class' => '\Domain\Entity\SnobUser')
            )
        );
        $sql = $this->sql($params);
        /* @var $oDBStatatement \RG\DataSource\Mysql\Statement */
        $oDBStatatement = $this->_connection->query($sql);
        $oDBStatatement->setFetch($params['__FETCH__']);
        $result = $oDBStatatement->fetchAll();
        return $result;
    }

   private function sql($params)
   {
       $snobdb = \Service\Config::get('database.snob.params.dbname');
       $defaultdb = \Service\Config::get('database.default.params.dbname');

       ob_start();
?>

SELECT
    `person`.*,

    `person`.`invited_by_project` AS `is_paid`,

    `payment`.`city`,

    `payment`.`country`,

    `delivery_user`.`id` AS `delivery_user_id`,

    `subscribe_plan`.`name` AS `subscribe_plan_name`,

    `subscribe_plan`.`title` AS `subscribe_plan_title`,

    IF (`person_partner`.`person_id`, 1, 0) AS `partner`,

    `delivery_user_email_exists`.`email` IS NOT NULL AS `delivery_user_email_exists`

FROM

    `<? echo $snobdb ?>`.`person`

    LEFT JOIN `<? echo $snobdb ?>`.`person_partner` ON true
       AND `person`.`id` = `person_partner`.`person_id`

    LEFT JOIN `<? echo $snobdb ?>`.`payment` ON true
       AND `person`.`payment_id` = `payment`.`id`

    LEFT JOIN `<? echo $snobdb ?>`.`subscribe_plan` ON true
        AND `person`.`subscribe_plan_id` = `subscribe_plan`.`id`

    LEFT JOIN `<? echo $defaultdb ?>`.`delivery_user` ON true
        AND `person`.`id` = `delivery_user`.`snob_user_id`

    LEFT JOIN `<? echo $defaultdb ?>`.`delivery_user` AS `delivery_user_email_exists` ON true
        AND `person`.`email` = `delivery_user_email_exists`.`email`


    LEFT JOIN `<? echo $snobdb ?>`.`person_settings_dict` AS `d` ON true
        AND `d`.`name` = 'all_from_delivery'
    LEFT JOIN `<? echo $snobdb ?>`.`person_settings_dict_notice` AS `dn` ON true
        AND `d`.`id` = `dn`.`person_settings_dict_id`
    LEFT JOIN `<? echo $snobdb ?>`.`person_settings` AS `s` ON true
        AND `d`.`id` = `s`.`person_settings_dict_id`
        AND `s`.`person_id` = `person`.`id`
    LEFT JOIN `<? echo $snobdb ?>`.`person_settings_notice` AS `sn` ON true
        AND `s`.`id` = `sn`.`person_settings_id`


WHERE
    true

    AND `person`.`email` IS NOT NULL
    AND `person`.`email` != ''

    AND COALESCE(`sn`.`email`, `dn`.`default_email`)

<? if ($params['id']): ?>
    AND `person`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['person_type_id']): ?>
    AND `person`.`person_type_id` IN ("<? echo join('", "', (array) $params['person_type_id']) ?>")
<? endif; ?>

<? if ($params['expiration_date']): ?>
    <? if (is_array($params['expiration_date'])): ?>
    AND `person`.`expiration_date` IN ("<? echo join('", "', (array) $params['expiration_date']) ?>")
    <? elseif (preg_match('/[^\d-\s:]/i', $params['expiration_date'])): ?>
    AND `person`.`expiration_date` <? echo $params['expiration_date'] ?>
    <? endif; ?>
<? endif; ?>

<? if ($params['is_partner']): ?>
    AND `person_partner`.`person_id` IS NOT NULL
<? endif; ?>

<? if ($params['subscribe_plan_name']): ?>
    AND `subscribe_plan`.`name` IN ("<? echo join('", "', (array) $params['subscribe_plan_name']) ?>")
<? endif; ?>

<? if ($params['modification_date']): ?>
    <? if (is_array($params['modification_date'])): ?>
    AND `person`.`modification_date` IN ("<? echo join('", "', (array) $params['modification_date']) ?>")
    <? elseif (preg_match('/[^\d-\s:]/i', $params['modification_date'])): ?>
    AND `person`.`modification_date` <? echo $params['modification_date'] ?>
    <? endif; ?>
<? endif; ?>

-- >8-]
GROUP BY
   `person`.`email`

<?
       $sql = ob_get_contents();
       ob_end_clean();
       return $sql;
   }
}
