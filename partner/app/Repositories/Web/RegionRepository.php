<?php

// +----------------------------------------------------------------------
// | 地址的资源库，处理资源库逻辑
// +----------------------------------------------------------------------
// | author     武安y<yaobin24@126.com>
// +----------------------------------------------------------------------
// | note
// +----------------------------------------------------------------------
// | date       2017/12/18 10:29
// +----------------------------------------------------------------------

namespace App\Repositories\Web;


use App\Model\Modian\Region;
use Illuminate\Support\Facades\DB;

class RegionRepository
{
    /**
     *  获取地区详情
     * @param $map
     * @return mixed
     */
    public function __getRegionInfo($map)
    {
        $result = Region::whereIn('id', $map)
            ->select('name')
            ->get()
            ->toArray();
        return $result ? implode('', array_column($result, 'name')) : false;
    }


    /**
     * 获取所有的城市id数组
     * @return mixed
     */
    public function __getAllRegionIdArr()
    {
        $resultArr = Region::select('id')->where('id','>',1)->get()->toArray();
        foreach ($resultArr as $key) {
            $result[$key['id']] = $key['id'];
        }
        return $result;
    }

    /**
     * 获取所有的城市列表
     * @return mixed
     */
    public function __getAllRegion()
    {
        $resultArr = Region::where('id','>',1)
                        ->select('id as value','name as label','parent_id')
                        ->get()
                        ->toArray();
        foreach ($resultArr as $key) {
            $result[$key['value']] = $key;
        }
        return $result;
    }

}