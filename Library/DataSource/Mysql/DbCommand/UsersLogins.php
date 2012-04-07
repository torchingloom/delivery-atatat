<?php

namespace DataSource\Mysql\DbCommand;

class UsersLogins extends DbCommand
{
	public function UsersLogins()
	{
        $oDBStatatement = $this->_connection->query("SELECT login FROM user");
        foreach ($oDBStatatement->fetchAll() AS $u)
        {
            $result[] = $u->login;
        }
        return $result;
	}
}
