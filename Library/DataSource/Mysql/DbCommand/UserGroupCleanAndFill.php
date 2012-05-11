<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupCleanAndFill extends DbCommand
{
    public function UserGroupCleanAndFill($iGroupId, \Domain\Collection\User $oCollection)
    {
        return array
        (
            'before' => \Service\Registry::get('db_default')->UserGroupClean($iGroupId),
            'now' => \Service\Registry::get('db_default')->UserGroupAppendUser($iGroupId, $oCollection),
        );
    }
}
