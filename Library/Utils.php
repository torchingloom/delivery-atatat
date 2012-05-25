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

    /**
     * Merge associative arrays, overwrite scalars and non-associative arrays.
     * Examples:
     * merge( { a: { b: c } }, { a: { d: e } } ) = { a: { b: c, d: e } }
     * merge( [ 1, 2 ], { a: b } ) = { 0: 1, 1: 2, a: b }
     * merge( anything, [ 1, 2 ] ) = [ 1, 2 ]
     * merge( anything, scalar ) = scalar
     */
    static function arrayMergeRecursiveDistinct()
    {
        $aArrays = func_get_args();
        $aMerged = $aArrays[0];
        for ($i = 1; $i < count($aArrays); $i++)
        {
            if (is_array($aArrays[$i]))
            {
                foreach ($aArrays[$i] AS $key => $val)
                {
                    if (is_int($key))
                    {
                        // Arrays with numeric keys overwrite each other
                        $aMerged = $aArrays[$i];
                        break;
                    }
                    elseif (!isset($aMerged[$key]) || !is_array($val))
                    {
                        $aMerged[$key] = $val;
                    }
                    else
                    {
                        $aMerged[$key] = self::arrayMergeRecursiveDistinct($aMerged[$key], $val);
                    }
                }
            }
        }
        return $aMerged;
    }

    static function arrayToObjectRecursive($arr, $classname = '\ArrayObject')
    {
        foreach ($arr as $key => &$item)
        {
            // Fast test for non-assoc arrays - do not convert them
            if (is_array($item) && !isset($item[0]))
            {
                $item = new $classname($item, \ArrayObject::ARRAY_AS_PROPS);
                Utils::arrayToObjectRecursive($item, $classname);
            }
        }
        return $arr;
    }

    static function objectToArrayRecursive($obj)
    {
        self::_objectToArrayRecursive($obj);
        return $obj;
    }

    private static function _objectToArrayRecursive(&$obj)
    {
        $obj = (array) $obj;
        foreach ($obj AS &$item)
        {
            if (is_object($item))
            {
                self::_objectToArrayRecursive($item);
            }
        }
    }

    static function arrayKeysToLowercaseRecursive($arr)
    {
        self::_arrayKeysToLowercaseRecursive($arr);
        return $arr;
    }

    private static function _arrayKeysToLowercaseRecursive(&$arr)
    {
        $arr = array_change_key_case($arr, CASE_LOWER);
        foreach ($arr AS &$item)
        {
            if (is_array($item))
            {
                self::_arrayKeysToLowercaseRecursive($item);
            }
        }
    }

    /**
     * Converts array into XML structure of SimpleXml object
     * @param array $data
     * @param \SimpleXml $xml
     */
    public static function array2xml($data, &$xml, $numeric_alias = null) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key) || !is_null($numeric_alias)) {
                    $k = !is_numeric($key) ? $key : $numeric_alias;
                    $subnode = $xml->addChild("$k");
                    self::array2xml($value, $subnode, $numeric_alias);
                } else {
                    self::array2xml($value, $xml, $numeric_alias);
                }
            } else {
                $xml->addChild("$key", "$value");
            }
        }
        return $xml;
    }


    /**
     * Convert generic object to associative array
     * @param mixed $xml
     * @return array
     */
    public static function xml2array($xml) {
        $result = array();
        $array = (array) $xml;
        foreach ($array as $key => $value) {
            $value = (array) $value;
            if (isset($value [0])) {
                $result[$key] = trim($value [0]);
            } else {
                $result[$key] = self::xml2array($value);
            }
        }
        if(is_array($result) && count($result) == 0) {
            $result = null;
        }
        return $result;
    }


    /**
     * Recursively convert encoding of string data found in array
     * @param mixed $data
     * @param string $from default 'windows-1251'
     * @param string $to default 'utf-8'
     * @return mixed
     */
    public static function iconv_recursive(&$data, $from = 'windows-1251', $to = 'utf-8') {
        array_walk_recursive($data, function(&$node) use($from, $to) {
            if(is_string($node)) {
                $node = iconv($from, $to, $node);
            }
        });
        return $data;
    }
}
