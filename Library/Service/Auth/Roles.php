<?php
namespace Service\Auth;

class Roles extends \Zend_Acl
{
    public function __construct ()
    {
		/*
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('login'));
        $this->add(new Zend_Acl_Resource('logout'));
        $this->add(new Zend_Acl_Resource('cronicle'));
        $this->add(new Zend_Acl_Resource('kafedra'));
        $this->add(new Zend_Acl_Resource('selected'));
        $this->add(new Zend_Acl_Resource('cabinet'));
        $this->add(new Zend_Acl_Resource('profile'));
        $this->add(new Zend_Acl_Resource('invitation'));
        $this->add(new Zend_Acl_Resource('promo'));
        $this->add(new Zend_Acl_Resource('webservices'));
		*/
        $this->add(new \Zend_Acl_Resource('entry'));
        $this->add(new \Zend_Acl_Resource('profile'));
		
		
        $this->addRole(new \Zend_Acl_Role('guest'));
        $this->addRole(new \Zend_Acl_Role('member'), 'guest');
        $this->addRole(new \Zend_Acl_Role('admin'), 'member');

        /*
        // Guest may only view content
		$this->allow('guest', 'invitation');
        $this->allow('guest', 'promo');
        $this->allow('guest', 'webservices', 'viewvideo');
		*/

        $this->allow('guest');
        $this->allow('member');
        $this->allow('admin');
    }
}