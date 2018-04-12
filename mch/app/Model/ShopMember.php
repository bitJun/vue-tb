<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ShopMember extends Model
{

    //protected $connection;
    protected $table = 'shop_member';
    
    public function __construct($shop_id = '') {
        parent::__construct();
        
        $this->table = $shop_id ? 'shop_member_'.($shop_id % 100) : '';
    }

    protected $guarded = ['id'];
    
}
