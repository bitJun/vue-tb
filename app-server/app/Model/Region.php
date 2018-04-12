<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(definition="Region", type="object")
 */
class Region extends Model
{
    protected $connection = 'modian_mysql';
    protected $table = 'region';
    protected $guarded = ['id'];
    public $timestamps = false;
}
