<?php

class IndexController extends \Controller_Action
{
    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();
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
}


