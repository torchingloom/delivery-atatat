<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $kind
 * @property mixed $name
 * @property mixed $subject
 * @property mixed $from
 * @property mixed $body_plain
 * @property mixed $body_html
 */

class Template extends Entity
{
    public function toString()
    {
        return $this->name;
    }

    public function isDeletable()
    {
        return $this->kind != 'system';
    }

    /**
     * @param array $vars
     * @return \Service\Mail\Message
     */
    public function messageBuild($vars)
    {
        $vars = array_merge(array('site_url' => \Service\Config::get('site.url')), $vars);

        $oMsg = new \Service\Mail\Message();

        $sBody = \Utils::sprintf($this->body_html, $vars, '%var');
        $sBody = \str_replace('../i/', \Service\Config::get('site.url') .'/i/', $sBody);

        $oMsg->body('html', $sBody);
        $oMsg->subject(\Utils::sprintf($this->subject, $vars, '%var'));
        return $oMsg;
    }
}
