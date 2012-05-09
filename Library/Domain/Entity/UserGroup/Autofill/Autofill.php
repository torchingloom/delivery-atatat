<?php

namespace Domain\Entity;

class UserGroup_Autofill
{
    /**
     * @var UserGroup
     */
    protected $oUserGroup;

    /**
     * @static
     * @param $algo
     * @param UserGroup $oUserGroup
     * @return UserGroup_Autofill
     */
    public static function factory($algo, UserGroup $oUserGroup)
    {
        $class = __CLASS__ .'_'. preg_replace("/_+(.)/e", 'strtoupper("\\1")', ucfirst($algo));
        $o = new $class ($oUserGroup);
        return $o;
    }

    protected function __construct($oUserGroup)
    {
        $this->oUserGroup = $oUserGroup;
    }

    public function fill()
    {
        // virtual
    }
}