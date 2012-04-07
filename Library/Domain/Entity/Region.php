<?php

namespace Domain\Entity;

class Region extends Entity
{
    public $url;

    protected static $regions = array('center', 'fareast', 'volga', 'northwest', 'siberia', 'ural', 'kavkaz', 'sevkavkaz');

    public function __construct()
    {
        $this->prepareUrl();
    }

    protected function prepareUrl()
    {
        $this->url = "/{$this->name}". self::_getNonRepPart();
    }

    protected static function _getNonRepPart()
    {
        static $s;
        if (!$s)
        {
            /* @var $r \Controller_Request */
            $r = \Service\Registry::get('request');
            $pathinfo = $r->getPathInfo();
            if (preg_match('/^\/('. join('|', self::$regions) .')$/', $pathinfo))  // ������ ��� � ������ ������
            {
            }
            elseif (preg_match('/^\/('. join('|', self::$regions) .').*/', $pathinfo))  // ������ ��� � ���-�� ���
            {
                $s = substr($pathinfo, strpos($pathinfo, '/', 2));
            }
            elseif (strpos($pathinfo, '/section') === 0) // ������
            {
                $s = $pathinfo;
            }

/*
            elseif (strpos($pathinfo, '/entry') === 0) // ��������
            {
                $s = $pathinfo;
            }
*/
        }
        return $s;
    }
}
