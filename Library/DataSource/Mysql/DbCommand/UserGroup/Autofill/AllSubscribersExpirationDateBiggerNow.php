<?php

namespace DataSource\Mysql\DbCommand;

class UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow extends DbCommand
{
    protected $params;

	public function UserGroup_Autofill_AllSubscribersExpirationDateBiggerNow($params = array())
	{
        $this->params = $this->ParamsAndFieldsPrepareByMethod
        (
            $params,
            array
            (
                'group_id' => null,
            )
        );


        $this->_connection->query($this->usersCreate())->execute();
    }

   private function usersCreate()
   {
       $snobdb = \Service\Config::get('database.snob.params.dbname');

       ob_start();
?>

   SELECT
        `person`.`id`
   FROM
       `<? echo $snobdb ?>`.`person`
   WHERE
       true


<?
       $sql = ob_get_contents();
       ob_end_clean();
       return $sql;
   }
}
