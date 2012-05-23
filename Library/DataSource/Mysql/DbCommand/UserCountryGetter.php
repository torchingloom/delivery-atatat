<?php

namespace DataSource\Mysql\DbCommand;

class UserCountryGetter extends DbCommand
{
	public function UserCountryGetter($params = array())
	{
        $params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                '__FETCH__' => array('class' => '\Domain\Entity\UserCountry')
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

   `delivery_user`.`country`

FROM

    `delivery_user`

WHERE
    true
    AND `delivery_user`.`country` IS NOT NULL
    AND `delivery_user`.`country` != ''

GROUP BY
   `delivery_user`.`country`

ORDER BY
   `delivery_user`.`country`

<?
       $sql = ob_get_contents();
       ob_end_clean();
       return $sql;
   }
}
