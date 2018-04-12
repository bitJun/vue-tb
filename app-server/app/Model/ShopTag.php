<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(definition="ShopTag", type="object")
 */
class ShopTag extends Model
{
    protected $connection = 'modian_mysql';  //库名
    protected $table = 'shop_tag';
    protected $guarded = ['id'];
    public $timestamps = false;
}
