<?php

if (! function_exists('getImageUrl')) {

    function getImageUrl($url)
    {
        if (stripos($url, 'http://') === FALSE && stripos($url, 'https://') === FALSE) {
            $url = env('QINIU_DOMAIN').$url;
        }
        return $url;
    }
}
