<?php
namespace App\Services\Shop;

use App\Facades\Gaode;
use App\Model\Region;
use App\Model\Shop;
use App\Model\ShopMongo;
use Illuminate\Support\Facades\Auth;

class ShopService
{
    public function getShop($id)
    {
        $data = Shop::where('id', $id)->first();
        if($data)
        {
            $data['province'] = Region::where('id',$data['province'])->first();
            $data['city'] = Region::where('id',$data['city'])->first();
            $data['district'] = Region::where('id',$data['district'])->first();

            $data['logo_url'] = getImageUrl($data['logo']);
        }
        return $data;
    }

    public function putShop($params)
    {
        $shop_id = Auth::user()->shop_id;

        $lng = 0;
        $lat = 0;
        $provinceName = Region::where('id',$params['province'])->value('name');
        $cityName = Region::where('id',$params['city'])->value('name');
        $districtName = Region::where('id',$params['district'])->value('name');
        $address = str_replace(' ',',',$params['address']);
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

        $data = [
            'province' => $params['province'],
            'city' => $params['city'],
            'tel' => $params['tel'],
            'lng' => $lng,
            'lat' => $lat,
            'district' => $params['district'],
            'address' => $params['address'],
        ];
        if(isset($params['logo']) && $params['logo'])
        {
            $data['logo'] = $params['logo']['img'];
        }

        if(Shop::where('id',$shop_id)->update($data))
        {
            $values = [
                'loc' => [
                    'longitude' => floatval($lng),
                    'latitude' => floatval($lat),
                ],
            ];
            ShopMongo::where('_id',$shop_id)->update($values);
        }
        return ['status'=>true];
    }

    public function decBalance($shop_id,$amount){

        Shop::where('id',$shop_id)->decrement('balance',$amount);
    }
}