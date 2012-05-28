<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $subject
 * @property mixed $from
 * @property mixed $body_plain
 * @property mixed $body_html
 * @property mixed $when_start
 * @property mixed $type
 * @property mixed $status
 * @property mixed $testemail
 */

class Task extends Entity
{
    public function send()
    {
        $oMailer = new \Service\Mail\Mailer();
        $oMailer->SetFrom($this->from);
        /** @var $oUser User */
        foreach ($this->getChilds('user') AS $oUser)
        {
            $oMailer->send($this->testemail ?: $oUser->email, $this->messageBuild($oUser));
        }
        return true;
    }

    /**
     * @static
     * @param User $oUser
     * @return \Service\Mail\Message
     */
    protected function messageBuild($oUser)
    {
        $oMsg = new \Service\Mail\Message();
        $oMsg->body('html', \Utils::sprintf($this->body_html, $oUser->toArray()));
        $oMsg->subject(\Utils::sprintf($this->subject, $oUser->toArray()));
        return $oMsg;
    }

    public function toString()
    {
        return $this->name;
    }

    public function appendUser(User $oUser)
    {
        parent::appendChild('user', $oUser);
    }

    public function appendUserTotalCount($iCount)
    {
        parent::setChildTotalCount('user', $iCount);
    }
}
