<?php

namespace Domain\Entity;

/**
 * @property mixed $id
 * @property mixed $status
 * @property mixed $kind
 * @property mixed $url
 * @property mixed $user_id
 * @property mixed $public_user_id
 * @property mixed $title
 * @property mixed $announce
 * @property mixed $body
 * @property mixed $creation_date
 * @property mixed $apply_date
 * @property mixed $publish_date
 * @property mixed $last_edit_date
 * @property mixed $comments_count
 * @property mixed $views_count
 * @property mixed $who_create
 *
 * @property mixed $parent_id
 * @property mixed $parent_url
 * @property mixed $parent_title
 * @property mixed $parent_announce
 * @property mixed $parent_publish_date
 *
 */

class Entry extends Entity
{
    public function __construct()
    {
        if ($this->parent_id)
        {
            $this->parent_url = "/entry/{$this->parent_id}";
//            $this->url = "{$this->parent_url}#c{$this->id}";
        }
        else
        {
            $this->url = "/entry/{$this->id}";
        }
    }

    public static function factory(Entry $o)
    {
        switch ($o->kind)
        {
            default:
            case 'entry':
                return $o;
            break;
            case 'comment':
                $class = '\Domain\Entity\EntryComment';
            break;
            case 'banner':
                $class = '\Domain\Entity\EntryBanner';
            break;
        }

        $newo = new $class();
        $newo->fill($o->toArray());
        return $newo;
    }

    public function fill($array)
    {
        $this->__data__ = $array;
    }

    public function __set($var, $value)
    {
        parent::__set($var, $value);
        if ($var == 'params' && $value && is_string($value))
        {
            $this->__data__ = array_merge($this->__data__, unserialize($value));
        }
    }

    public function titleCutted($howmatch = 10000)
    {
        return \Service\Utils::textCut($this->title, $howmatch, true);
    }

    public function announceCutted($howmatch = 10000)
    {
        return \Service\Utils::textCut($this->announce, $howmatch, true);
    }

    public function bodyCutted($howmatch = 100000, $bStripTagsAndBBCodes = false)
    {
        return \Service\Utils::textCut($this->body, $howmatch, $bStripTagsAndBBCodes);
    }

    public function appendAuthor($oUser)
    {
        if (!$oUser)
        {
            return;
        }
        parent::appendChild('Author', $oUser);
    }

    public function appendCommentator($oUser)
    {
        if (!$oUser)
        {
            return;
        }
        parent::appendChild('Commentator', $oUser);
    }

    public function appendThread($oEntryGroupThread)
    {
        if (!$oEntryGroupThread)
        {
            return;
        }
        if (is_array($oEntryGroupThread))
        {
            foreach ($oEntryGroupThread AS $oThread)
            {
                parent::appendChild('Thread', $oThread);
            }
        }
        else
        {
            parent::appendChild('Thread', $oEntryGroupThread);
        }
    }

    /**
     * @param \Domain\Entity\Entry $oEntry
     * @return
     */
    public function appendEntryParent($oEntry)
    {
        if (!$oEntry)
        {
            return;
        }
        parent::appendChild('EntryParent', $oEntry);
        $this->parent_id = $oEntry->id;
        $this->parent_title = $oEntry->title;
        $this->parent_announce = $oEntry->announce;
        $this->parent_publish_date = $oEntry->publish_date;
    }
}
