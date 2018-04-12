<?php

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

if (! function_exists('getApiDomain')) {

    function getApiDomain()
    {
        return 'http://'.env('API_DOMAIN').'/';
    }
}

if (! function_exists('getPayDomain')) {

    function getPayDomain()
    {
        return 'http://'.env('PAY_DOMAIN').'/';
    }
}
