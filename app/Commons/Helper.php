<?php


namespace App\Commons;


class Helper
{
    public static function convertFullWidth(&$params, $key)
    {
        if(!empty($params[$key]))
            $params[$key] = mb_convert_kana($params[$key], 'KVRN');
    }

    public static function convertHalfWidth(&$params, $key)
    {
        if(!empty($params[$key]))
            $params[$key] = mb_convert_kana($params[$key], 'kvrn');
    }

}
