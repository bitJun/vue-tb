<?php

/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/6/20
 * Time: 下午8:09
 */

namespace App\Services\Shop;

use App\Model\Tag;

class TagService
{
    public function __construct() {
    }

    public function getTags()
    {
        $tags = Tag::get();
        return $tags;
    }
}