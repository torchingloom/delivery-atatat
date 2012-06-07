<?php

namespace DataSource\Mysql\DbCommand;

class SnobTemplateGetter extends DbCommand
{
	public function SnobTemplateGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'deleted' => null,
                'order' => 'creation_date',
                '__FETCH__' => array('class' => '\Domain\Entity\SnobTemplate')
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
       $olddeliverydb = \Service\Config::get('database.old_delivery.params.dbname');

       ob_start();
?>

SELECT
    *
FROM

    `<? echo $olddeliverydb ?>`.`templates`

WHERE
    true

<? if (!is_null($params['deleted'])): ?>
    AND `templates`.`deleted` IN ("<? echo join('", "', (array) $params['deleted']) ?>")
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
