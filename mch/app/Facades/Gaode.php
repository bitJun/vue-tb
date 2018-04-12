<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/8/21
 * Time: 下午7:48
 */
class Gaode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gaode';
    }
}