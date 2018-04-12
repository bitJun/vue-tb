<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(definition="Order", type="object")
 */
class Order extends Model
{
    protected $connection = 'modian_mysql';
    protected $table = '';
    protected $guarded = [];

    public function __construct($tableSuffix = '') {
        parent::__construct();
        $this->table = $tableSuffix ? 'order_'.$tableSuffix : 'order_0';
    }
}
