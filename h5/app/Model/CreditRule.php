<?php
/**
 * Created by PhpStorm.
 * User: along
 * Date: 17/6/21
 * Time: 下午2:36
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CreditRule extends Model {
    protected $table = 'credit_Rule';
    protected $guarded = ['id'];
}