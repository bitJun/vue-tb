<?php
/**
 * Created by PhpStorm.
 * User: yaobin
 * Date: 2017/12/18
 * Time: 下午4:35
 */

namespace App\Model\Moker;


use Illuminate\Database\Eloquent\Model;

class Moker extends Model
{

    protected $connection = 'moker_mysql';
    protected $table = 'moker';
    protected $guarded = ['id'];
    public $timestamps = false;
}