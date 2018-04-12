<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ShopMongo extends Eloquent
{
    protected $connection = 'mongodb';  //库名
    protected $collection = 'shop';     //文档名
    protected $primaryKey = '_id';    //设置id
    protected $fillable = ['_id','title','tag_id','loc'];  //设置字段白名单
    //protected $guarded = ['_id'];
}
