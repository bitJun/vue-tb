<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $table = '';
    protected $guarded = ['id'];

    public function __construct($tableSuffix = '') {
        parent::__construct();

        $this->table = $tableSuffix ? 'order_'.$tableSuffix : 'order_0';
    }

}
