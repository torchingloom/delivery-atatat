<?php

namespace Domain\Collection;

class User extends Collection
{
    public function store(array $data = null, array $args = null)
    {
        $result = parent::store($data, $args);
        if (!empty($result['create']))
        {
            $oTpl = self::messageTemplateConfirm();
            $oMailer = new \Service\Mail\Mailer();
            $oMailer->SetFrom($oTpl->from);

            /** @var $oUser \Domain\Entity\User */
            foreach ($result['create'] AS $oUser)
            {
                if ($oUser->status == 'pending' && $oUser->activate_code)
                {
                    $oMailer->send($oUser->email, $oTpl->messageBuild($oUser->toArray()));
                }
            }
        }
        return $result;
    }

    public function activateAllChilds()
    {
        $oTpl = self::messageTemplateBless();
        $oMailer = new \Service\Mail\Mailer();
        $oMailer->SetFrom($oTpl->from);

        /** @var $oUser \Domain\Entity\User */
        foreach ($this->content AS &$oUser)
        {
            if ($oUser->status == 'pending' && $oUser->activate_code)
            {
                $oUser->status = 'normal';
                $oUser->activate_code = null;
                // 2 sorry
                $oMailer->send($oUser->email, $oTpl->messageBuild($oUser->toArray()));
            }
        }
        // 1 sorry
        return $this->store();
    }

    /**
     * @static
     * @return \Domain\Entity\Template
     */
    protected static function messageTemplateConfirm()
    {
        $oModel = new \Domain\Model\Template(array('id' => 1));
        return $oModel->getCollection('list')->current();
    }
    /**
     * @static
     * @return \Domain\Entity\Template
     */
    protected static function messageTemplateBless()
    {
        $oModel = new \Domain\Model\Template(array('id' => 2));
        return $oModel->getCollection('list')->current();
    }
}