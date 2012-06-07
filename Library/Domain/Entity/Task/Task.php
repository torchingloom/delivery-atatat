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
        $result = array();
        $oMailer = new \Service\Mail\Mailer();
        $oMailer->SetFrom($this->from);
        if ($aUsers = $this->getChilds('user'))
        {
            /** @var $oUser User */
            foreach ($this->getChilds('user') AS $oUser)
            {
                if ($oUser->when_send)
                {
                    continue;
                }
                if ($oMailer->send($this->testemail ?: $oUser->email, $this->messageBuild($oUser)))
                {
                    $result[] = $oUser->id;
                }
            }
        }
        if (!$this->testemail && $result)
        {
            $this->userMarkAsSend($result);
        }
        return array('sendto' => $result, 'status' => $this->tryMarkAsComplete());
    }

    /**
     * @static
     * @param User $oUser
     * @return \Service\Mail\Message
     */
    protected function messageBuild($oUser)
    {
        $oMsg = new \Service\Mail\Message();

        $sBody = \Utils::sprintf($this->body_html, $oUser->toArray(), '%var');
        $sBody = \str_replace('../i/', \Service\Config::get('site.url') .'/i/', $sBody);

        $oMsg->body('html', $sBody);
        $oMsg->subject(\Utils::sprintf($this->subject, $oUser->toArray(), '%var'));
        return $oMsg;
    }

    protected function userMarkAsSend($who)
    {
        return \Service\Registry::get('db_default')->TaskUserMarkAsSet($this->id, $who);
    }

    protected function tryMarkAsComplete()
    {
        return \Service\Registry::get('db_default')->TaskTryMarkAsComplete($this->id);
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
