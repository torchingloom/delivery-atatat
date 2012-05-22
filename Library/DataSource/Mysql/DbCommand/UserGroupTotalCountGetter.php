<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupTotalCountGetter extends DbCommand
{
	public function UserGroupTotalCountGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'algo' => null,
            )
        );
        $sql = $this->sql($params);

        /* @var $oDBStatatement \RG\DataSource\Mysql\Statement */
        $oDBStatatement = $this->_connection->query($sql);
        $result = $oDBStatatement->fetchColumn();
        return $result;
    }

   private function sql($params)
   {
           ob_start();
?>

   SELECT
       COUNT(*)
   FROM
       `delivery_user_group`
   WHERE
        true

<? if ($params['id']): ?>
       AND `delivery_user_group`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['algo']): ?>
   <? if ($params['algo'] == 'isNotNull'): ?>
        AND `delivery_user_group`.`algo` IS NOT NULL
    <? else: ?>
        AND `delivery_user_group`.`algo` IN ("<? echo join('", "', (array) $params['algo']) ?>")
    <? endif; ?>
<? endif; ?>

<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}
