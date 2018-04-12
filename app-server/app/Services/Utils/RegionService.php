<?php

namespace App\Services\Utils;
use App\Model\Region;

/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/8
 * Time: 下午3:33
 */
class RegionService
{
    public function getRegions($parent_id=0)
    {
        $regions = Region::where('parent_id',$parent_id)->get();
        return $regions;
    }

    public function getRegion($id)
    {
        $region = Region::where('id',$id)->first();
        return $region;
    }
}