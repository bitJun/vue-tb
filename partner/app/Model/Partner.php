<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    public $table = 'partner';
    protected $guarded = ['id'];

    /**
     * 批量赋值的属性
     *
     * @var array
     */
    protected $fillable = ['parent_id','name','mobile','company_name','manager','province_id',
        'city_id','district_id','address','balance','income','disabled','expire_at'];
}