<?php

if (! function_exists('weekDayFormat')) {
    function weekDayFormat($l)
    {
        $weekArr = [
            'Sunday' => '星期日',
            'Monday' => '星期一',
            'Tuesday' => '星期二',
            'Wednesday' => '星期三',
            'Thursday' => '星期四',
            'Friday' => '星期五',
            'Saturday' => '星期六'
        ];
        $res = isset($weekArr[$l]) ? $weekArr[$l] : '';
        return $res;
    }
}

if (! function_exists('beautifyTime')) {

    function beautifyTime($time, $style=0)
    {
        $btime = date('Y年m月d日 H:i:s', $time);
        $diffTime = time() - $time;
        $diffTime2 = time() - strtotime(date('Y-m-d', $time));
        if($style == 0) { //微信朋友圈的风格
            if($diffTime <= 60) {
                $btime = floor($diffTime) . '秒前';
            } elseif($diffTime <= 60*60 && $diffTime > 60) {
                $btime = floor(($diffTime / 60)) . '分钟前';
            } elseif($diffTime <= 24*60*60 && $diffTime > 60*60) {
                $btime = floor(($diffTime / 60 / 60)) . '小时前';
            } elseif($diffTime <= 48*60*60 && $diffTime > 24*60*60) {
                $btime = '昨天';
            } elseif($diffTime <= 30*24*60*60 && $diffTime > 48*60*60) {
                $btime = floor(($diffTime / 60 / 60 / 30)) . '天前';
            } elseif($diffTime <= 31536000 && $diffTime > 30*24*60*60) {
                $btime = floor(($diffTime / 60 / 60 / 30 / 12)) . '个月前';
            }  elseif($diffTime > 31536000) {
                $btime = floor(($diffTime / 60 / 60 / 30 / 12 / 12)) . '年前';
            }
        }else if($style == 1) { //微信聊天窗口的风格
            if($diffTime2 <= 24*60*60) {
                $btime = date('H:i', $time);
            } elseif($diffTime2 <= 48*60*60 && $diffTime2 > 24*60*60) {
                $btime = '昨天 '.date('H:i', $time);
            } elseif($diffTime2 <= 7*24*60*60 && $diffTime2 > 48*60*60) {
                $l = date('l', $time);
                $btime = weekDayFormat($l).' '.date('H:i', $time);
            } elseif($diffTime2 > 7*24*60*60) {
                $btime = date('Y年m月d日 H:i', $time);
            }
        }

        return $btime;
    }
}

if(!function_exists('_createSmsStr'))
{
    function _createSmsStr()
    {
        /* 选择一个随机的方案 */
        mt_srand((double)microtime() * 1000000);
        $str = str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
        return $str;
    }
}

if (! function_exists('getImageUrl')) {

    function getImageUrl($url)
    {
        if(!$url)
        {
            return '';
        }
        if (stripos($url, 'http://') === FALSE && stripos($url, 'https://') === FALSE) {
            $url = env('QINIU_DOMAIN').$url;
        }
        return $url;
    }
}

if (! function_exists('getModianUrl')) {

    function getModianUrl()
    {
        $domain = 'http://'.env('MODIAN_DOMAIN');
        return $domain;
    }
}

