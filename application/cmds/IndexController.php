<?php

class IndexController extends \Controller_Action
{
    protected $vars;

    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
        $this->vars = explode('|', $this->_request->getParam('vars'));
    }

    public function groupsAutofillAction()
    {
        $oModel = new \Domain\Model\UserGroup(array('algo' => 'isNotNull'));
        /* @var $oCollection \Domain\Collection\UserGroup */
        $oCollection = $oModel->getCollection('list');
        foreach ($oCollection->autofill() AS $res)
        {
            echo "\nGroup: #{$res['group']->id} {$res['group']->name}\n{$res['group']->algo}\nUsers: add {$res['result']['snobuser']['insert']}, upd {$res['result']['snobuser']['update']}\nIn group: before {$res['result']['group']['before']}, now {$res['result']['group']['now']}\n";
        }
    }

    public function downloadDataAction()
    {
        $cfg = \Service\Config::get('database.default.params');
        $where = APPLICATION_PATH .'data/backup/'. (@$this->vars[0] ?: date('YmdHis')) . '.sql';
        system("mysqldump --opt -h {$cfg->host} -u {$cfg->username} -p{$cfg->password} {$cfg->dbname} > {$where}");
    }
}


