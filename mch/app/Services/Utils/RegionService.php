<?php
namespace App\Services\Utils;

use App\Model\Region;
use Illuminate\Support\Facades\Cache;

class RegionService
{
    public function getRegionsByTree()
    {
        $res = [];
        if(Cache::get('regions:tree'))
        {
            $res =  Cache::get('regions:tree');
        }else{
            $regions = Region::orderBy('listorder')->get();
            foreach ($regions as $_region) {
                $res[$_region->parent_id][] = $_region;
            }
            Cache::forever('shop_regions',$res);
        }

        return $res;
    }
}