<?php

namespace DataSource\Mysql\DbCommand;

class TaskGetter extends DbCommand
{
	public function TaskGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'type' => null,
                'status' => null,
                'when_start' => null,
                '__FETCH__' => array('class' => '\Domain\Entity\Task')
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
           ob_start();
?>

   SELECT
       *
   FROM
       `delivery_task`
   WHERE
       true
<? if ($params['when_start']): ?>
   <? if (is_array($params['when_start'])): ?>
   AND `delivery_task`.`when_start` IN ("<? echo join('", "', (array) $params['when_start']) ?>")
   <? elseif (preg_match('/w+/i', $params['when_start'])): ?>
   AND `delivery_task`.`when_start` <? echo $params['when_start'] ?>
   <? endif; ?>
<? endif; ?>

<? if ($params['type']): ?>
       AND `delivery_task`.`type` IN ("<? echo join('", "', (array) $params['type']) ?>")
<? endif; ?>
<? if ($params['status']): ?>
       AND `delivery_task`.`status` IN ("<? echo join('", "', (array) $params['status']) ?>")
<? endif; ?>
<? if ($params['id']): ?>
       AND `delivery_task`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}
