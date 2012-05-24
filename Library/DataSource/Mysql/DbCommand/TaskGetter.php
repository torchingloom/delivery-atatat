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
<? if ($params['id']): ?>
       AND `delivery_task`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}
