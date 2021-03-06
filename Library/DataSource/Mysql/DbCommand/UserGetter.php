<?php

namespace DataSource\Mysql\DbCommand;

class UserGetter extends DbCommand
{
	public function UserGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'isnew' => null,
                'query' => null,
                'snob_user_id' => null,
                'subscribe_end_date' => null,
                'snob_person_type' => null,
                'snob_person_partner' => null,
                'group_id' => null,
                'task_id' => null,
                'filters' => null,
                'noorder' => null,
                'status' => 'normal',
                'activate_code' => null,
                '__FETCH__' => array('class' => '\Domain\Entity\User'/*, 'factory' => '\Domain\Entity\User::factory'*/)
            )
        );

        if ($params['isnew'])
        {
            return array();
        }

        $sql = $this->sql($params);

        /* @var $oDBStatatement \RG\DataSource\Mysql\Statement */
        $oDBStatatement = $this->query($sql);
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

    `delivery_user`.*

<? if ($params['group_id']): ?>

    , `delivery_user_to_group`.`group_id`

<? elseif ($params['task_id']): ?>

    , `delivery_user_to_task`.`task_id`
    , `delivery_user_to_task`.`when_send`

<? endif; ?>

FROM

    `<? echo $defaultdb ?>`.`delivery_user`

<? if ($params['group_id']): ?>

    JOIN `<? echo $defaultdb ?>`.`delivery_user_to_group` ON true
        AND `delivery_user`.`id` = `delivery_user_to_group`.`user_id`
        AND `delivery_user_to_group`.`group_id` IN ("<? echo join('", "', (array) $params['group_id']) ?>")

<? elseif ($params['task_id']): ?>

    JOIN `<? echo $defaultdb ?>`.`delivery_user_to_task` ON true
        AND `delivery_user`.`id` = `delivery_user_to_task`.`user_id`
        AND `delivery_user_to_task`.`task_id` IN ("<? echo join('", "', (array) $params['task_id']) ?>")

<? endif; ?>

WHERE
    true

<? if ($params['status']): ?>
    AND `delivery_user`.`status` IN ("<? echo join('", "', (array) $params['status']) ?>")
<? endif; ?>

<? if ($params['activate_code']): ?>
    AND `delivery_user`.`activate_code` IN ("<? echo join('", "', (array) $params['activate_code']) ?>")
<? endif; ?>

<? if ($params['id']): ?>
    AND `delivery_user`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['snob_person_type']): ?>
    AND `delivery_user`.`snob_person_type` IN ("<? echo join('", "', (array) $params['snob_person_type']) ?>")
<? endif; ?>

<? if ($params['snob_person_partner']): ?>
    AND `delivery_user`.`snob_person_partner` = 1
<? endif; ?>

<? if ($params['snob_user_id']): ?>
    <? if (is_array($params['snob_user_id'])): ?>
    AND `delivery_user`.`snob_user_id` IN ("<? echo join('", "', (array) $params['snob_user_id']) ?>")
    <? elseif (preg_match('/[^0-9]/ims', $params['snob_user_id'])): ?>
    AND `delivery_user`.`snob_user_id` <? echo $params['snob_user_id'] ?>
    <? endif; ?>
<? endif; ?>

<? if ($params['subscribe_end_date']): ?>
    <? if (is_array($params['subscribe_end_date'])): ?>
    AND `delivery_user`.`subscribe_end_date` IN ("<? echo join('", "', (array) $params['subscribe_end_date']) ?>")
    <? elseif (preg_match('/w+/i', $params['subscribe_end_date'])): ?>
    AND `delivery_user`.`subscribe_end_date` <? echo $params['subscribe_end_date'] ?>
    <? endif; ?>
<? endif; ?>

<? if ($params['query']): ?>
    AND
    (
        false
        OR `delivery_user`.`first_name` LIKE '%<? echo $params['query'] ?>%'
        OR `delivery_user`.`last_name` LIKE '%<? echo $params['query'] ?>%'
        OR `delivery_user`.`email` LIKE '%<? echo $params['query'] ?>%'
    )
<? endif; ?>

<? if ($params['filters']): ?>
    <? $filters = $params['filters']; ?>
    <? if (!empty($filters['paid']) && !(array_key_exists('free', $filters['paid']) && array_key_exists('paid', $filters['paid']))): ?>

    AND
    (
        false
        OR `delivery_user`.`snob_user_id` IS NULL
        OR `delivery_user`.`is_paid` = <? echo (int) empty($filters['paid']) ?>
    )

    <? endif; ?>
    <? if (!empty($filters['subscribe_end_date'])): ?>

    AND
    (
        false
        OR `delivery_user`.`snob_user_id` IS NULL
        OR `delivery_user`.`subscribe_end_date` <= '<? echo $filters['subscribe_end_date'] ?>'
    )

    <? endif; ?>
    <? if (!empty($filters['subscribe_start_date'])): ?>

    AND
    (
        false
        OR `delivery_user`.`snob_user_id` IS NULL
        OR `delivery_user`.`subscribe_start_date` >= '<? echo $filters['subscribe_start_date'] ?>'
    )

    <? endif; ?>
    <? if (!empty($filters['city'])): ?>

    AND
    (
        false
        OR `delivery_user`.`snob_user_id` IS NULL
        OR `delivery_user`.`city` IN ("<? echo join('","', $filters['city']) ?>")
    )

    <? endif; ?>
    <? if (!empty($filters['country'])): ?>

    AND
    (
        false
        OR `delivery_user`.`snob_user_id` IS NULL
        OR `delivery_user`.`country` IN ("<? echo join('","', $filters['country']) ?>")
    )

    <? endif; ?>
<? endif; ?>

<? if (!$params['noorder']): ?>

ORDER BY
   `delivery_user`.`last_name`,
   `delivery_user`.`first_name`

<? endif; ?>

<?
       $sql = ob_get_contents();
       ob_end_clean();
       return $sql;
   }
}
