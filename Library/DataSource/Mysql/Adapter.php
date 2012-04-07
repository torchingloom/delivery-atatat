<?php

namespace DataSource\Mysql;

class Adapter extends \Zend_Db_Adapter_Pdo_Mysql
{
    protected $_defaultStmtClass = '\DataSource\Mysql\Statement';

    public function query($sql, $bind = array())
    {
        if (\Service\Log\Log::isEnabled())
        {
            $dbgbcktrc = debug_backtrace();
            $LogUnit = \Service\Log\Log::instance('DB')->unit($dbgbcktrc[1]['function'], preg_replace("!\s+!", ' ', $sql));
        }

        $rs = parent::query($sql, $bind);

        if (!empty($LogUnit))
        {
            $LogUnit->checkout();
        }

        return $rs;
    }
}
