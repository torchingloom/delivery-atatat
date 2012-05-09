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
           ob_start();
?>

   SELECT
       *
   FROM
       `person`
   WHERE
       true

<? if ($params['id']): ?>
       AND `person`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
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
