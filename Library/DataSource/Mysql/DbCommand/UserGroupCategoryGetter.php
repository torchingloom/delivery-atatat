<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupCategoryGetter extends DbCommand
{
	public function UserGroupCategoryGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'order' => 'id',
                '__FETCH__' => array('class' => '\Domain\Entity\UserGroupCategory')
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
       `delivery_user_group_category`
   WHERE
        true

<? if ($params['id']): ?>
       AND `delivery_user_group_category`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['order']): ?>

   ORDER BY
   <? echo join(",\n", (array) $params['order']) ?>

<? endif; ?>


<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}
