<?php

namespace App\Common;

class Utils
{

    /**
     * 获取唯一id 3=18 5=20
     */
    public static function getUniqueId($len=3)
    {
        $rand_max = intval(\str_repeat('9', $len));
        list($micro, $sec) = \microtime();
        return date('ymdHis') . 
            str_pad(intval($micro * 1000), 3, '0', STR_PAD_LEFT) . 
            str_pad(mt_rand(1, $rand_max), $len, '0', STR_PAD_LEFT);
    }

    public static function short($url)
    {
        $code = floatval(sprintf('%u', crc32($url)));
     
        $surl = '';
     
        while($code){
            $mod = fmod($code, 62);
            if($mod>9 && $mod<=35){
                $mod = chr($mod + 55);
            }elseif($mod>35){
                $mod = chr($mod + 61);
            }
            $surl .= $mod;
            $code = floor($code/62);
        }
     
        return $surl;
    }
}
