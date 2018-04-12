<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(definition="Tag", type="object")
 */
class Tag extends Model
{
    protected $connection = 'modian_mysql';
    protected $table = 'tag';
    protected $guarded = ['id'];
    public $timestamps = false;
}
