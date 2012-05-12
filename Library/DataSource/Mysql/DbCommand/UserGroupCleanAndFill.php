<?php

namespace DataSource\Mysql\DbCommand;

class UserGroupCleanAndFill extends DbCommand
{
    public function UserGroupCleanAndFill($iGroupId, \Domain\Collection\User $oCollection)
    {
        \Service\Registry::get('db_default')->UserGroupFieldSetter(array($iGroupId => array('when_autofill' => date('Y-m-d H:i:s'))));
        return array
        (
            'before' => \Service\Registry::get('db_default')->UserGroupClean($iGroupId),
            'now' => \Service\Registry::get('db_default')->UserGroupAppendUser($iGroupId, $oCollection),
        );
    }
}
