<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupGetter extends DbCommand
{
	public function UserGroupGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'algo' => null,
                '__FETCH__' => array('class' => '\Domain\Entity\UserGroup')
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

    ORDER BY
        `name`

<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}