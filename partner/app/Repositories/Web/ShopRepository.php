<?php

// +----------------------------------------------------------------------
// | 店铺的资源库，处理资源库逻辑
// +----------------------------------------------------------------------
// | author     ldw1007@sina.cn
// +----------------------------------------------------------------------
// | note
// +----------------------------------------------------------------------
// | date       2017/12/18 10:29
// +----------------------------------------------------------------------

namespace App\Repositories\Web;


use App\Facades\Gaode;
use App\Model\Region;
use App\Model\Shop;
use App\Model\ShopMongo;
use App\Model\ShopTag;

class ShopRepository
{
    public function __putShopTag($shop_id,$tag_id)
    {
        ShopTag::where('shop_id',$shop_id)->delete();
        ShopTag::create([
            'shop_id'=>$shop_id,
            'tag_id'=>$tag_id
        ]);
    }

    public function __putShopMongo($shop_id,$address)
    {
        $shop = $this->__getShop($shop_id);
        if(!$shop)
        {
            return false;
        }

        $lng = 0;
        $lat = 0;
        $provinceName = Region::where('id',$shop['province'])->value('name');
        $cityName = Region::where('id',$shop['city'])->value('name');
        $districtName = Region::where('id',$shop['district'])->value('name');
        $address = str_replace(' ',',',$address);
        $address = $provinceName.$cityName.$districtName.$address;
        $gaode = Gaode::geoCode($address);
        $gaode = json_decode($gaode,true);
        if($gaode['status'] == 1)
        {
            $geocodes = $gaode['geocodes'];
            if($geocodes)
            {
                $location = $geocodes[0]['location'];
                $locations = explode(',',$location);
                $lng = $locations[0];
                $lat = $locations[1];
            }
        }

        $values = [
            'loc' => [
                'longitude' => floatval($lng),
                'latitude' => floatval($lat),
            ],
        ];
        ShopMongo::where('_id',$shop_id)->update($values);
    }

    public function __getShop($shop_id)
    {
        return Shop::where('id',$shop_id)->first();
    }
}