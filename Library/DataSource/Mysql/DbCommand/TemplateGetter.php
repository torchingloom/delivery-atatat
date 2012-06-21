<?php

namespace DataSource\Mysql\DbCommand;

class TemplateGetter extends DbCommand
{
	public function TemplateGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'id' => null,
                'kind' => null,
                '__FETCH__' => array('class' => '\Domain\Entity\Template')
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
        `delivery_template`
    WHERE
        true

<? if ($params['id']): ?>
        AND `delivery_template`.`id` IN ("<? echo join('", "', (array) $params['id']) ?>")
<? endif; ?>

<? if ($params['kind']): ?>
        AND `delivery_template`.`kind` IN ("<? echo join('", "', (array) $params['kind']) ?>")
<? endif; ?>

    ORDER BY
        `delivery_template`.`kind` DESC,
        `delivery_template`.`id`

<?
           $sql = ob_get_contents();
           ob_end_clean();
           return $sql;
       }
}
