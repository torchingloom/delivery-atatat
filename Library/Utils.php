<?php

class Utils
{
    static function printr($array = array())
    {
        if (APPLICATION_ENVIRONMENT == 'staging' && $array)
        {
            if (isset($_SERVER['HTTP_HOST']) && empty($_SERVER['argv']))
            {
                echo('<PRE style="color: green; background-color: silver; font-size: 10px; font-family: verdana; font-weight: bold; padding: 4px; position: pabsolute; z-index: 1000">');
                print_r($array);
                echo('</PRE>');
                return;
            }
            echo "\n\n";
            print_r($array);
            echo "\n\n";
            return;
        }
    }

    static function textCut($txt, $length, $bStripTagsAndBBCodes = false)
    {
        if ($bStripTagsAndBBCodes)
        {
            $txt = preg_replace('![(|/).*]!ims', ' ', $txt);
            $txt = strip_tags($txt);
        }
        if (strlen($txt) <= $length)
        {
            return $txt;
        }
        while (preg_replace('![^0-9a-zа-я]!i', '', @$txt[$length]))
        {
            $length--;
        }

        $txt = substr($txt, 0, $length) .' ...';
        $txt = str_replace('"', '&quot;', $txt);

        return $txt;
    }

    static function translate($str)
    {

        static $reg, $rep;
        if (!$reg)
        {
            $reg = array('а', 'б', 'в', 'г', 'д', 'е|ё', 'ж', 'з', 'и|й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч',  'ш|щ', 'ъ|ь', 'ы', 'э', 'ю', 'я', 'А', 'Б', 'В', 'Г', 'Д', 'Е|Ё', 'Ж', 'З', 'И|Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч',  'Ш|Щ', 'Ъ|Ь', 'Ы', 'Э', 'Ю', 'Я');
            $rep = array('a', 'b', 'v', 'g', 'd', 'e',   'j', 'z', 'i',   'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh',  '',    'i', 'e', 'u', 'ya', 'A', 'B', 'V', 'G', 'D', 'E',   'J', 'Z', 'I',   'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh',  '',    'I', 'E', 'U', 'Ya');
            array_walk($reg, function (&$valuev, $key) { $valuev = "/{$valuev}/is"; });
        }
        return preg_replace($reg, $rep, $str);
    }

    static function pluralNumeric($n, $f1, $f2, $f5)
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) return $f5;
        if ($n1 > 1 && $n1 < 5) return $f2;
        if ($n1 == 1) return $f1;
        return $f5;
    }
}
