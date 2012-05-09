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
                'person_type_id' => null,
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

       ob_start();
?>

SELECT
    `person`.*,
    `payment`.`city`,
    `subscribe_plan`.`name` AS `subscribe_plan_name`,
    `subscribe_plan`.`title` AS `subscribe_plan_title`,

    `delivery_user`.`id` AS `delivery_user_id`,

    IF (`person_partner`.`person_id`, 1, 0) AS `partner`
FROM
    `<? echo $snobdb ?>`.`person`

   LEFT JOIN `<? echo $snobdb ?>`.`person_partner` ON true
       AND `person`.`id` = `person_partner`.`person_id`

   LEFT JOIN `<? echo $snobdb ?>`.`payment` ON true
       AND `person`.`payment_id` = `payment`.`id`

    LEFT JOIN `<? echo $snobdb ?>`.`subscribe_plan` ON true
        AND `person`.`subscribe_plan_id` = `subscribe_plan`.`id`

    LEFT JOIN `delivery_user` ON true
        AND `person`.`id` = `delivery_user`.`snob_user_id`


WHERE
    true

    AND `person`.`email` IS NOT NULL

<? if ($params['id']): ?>
    AND `person`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['person_type_id']): ?>
    AND `person`.`person_type_id` IN ("<? echo join('", "', (array) $params['person_type_id']) ?>")
<? endif; ?>

<? if ($params['expiration_date']): ?>
    <? if (is_array($params['expiration_date'])): ?>
    AND `person`.`expiration_date` IN ("<? echo join('", "', (array) $params['expiration_date']) ?>")
    <? elseif (preg_match('/w+/i', $params['expiration_date'])): ?>
    AND `person`.`expiration_date` <? echo $params['expiration_date'] ?>
    <? endif; ?>
<? endif; ?>


<?
       $sql = ob_get_contents();
       ob_end_clean();
       return $sql;
   }
}
