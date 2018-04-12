<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopMember extends Model
{
    protected $table = '';
    protected $guarded = [];


    public function __construct($member_id = '') {
        parent::__construct();
        $this->table = $member_id ? 'shop_member_'.($member_id % 100) : '';
    }
}
