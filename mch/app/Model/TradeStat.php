<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/11/4
 * Time: 下午4:17
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class TradeStat extends Model
{
    protected $table = 'trade_stat';

    protected $guarded = ['id'];
    public $timestamps = false;

}