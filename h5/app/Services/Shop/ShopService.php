<?php
namespace App\Services\Shop;

use App\Model\Shop;

class ShopService
{
    public function getShop($id)
    {
        $data = Shop::where('id', $id)->first();
        return $data;
    }
}