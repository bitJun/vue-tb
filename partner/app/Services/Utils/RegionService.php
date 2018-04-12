<?php

namespace App\Services\Utils;
use App\Model\Region;
use App\Repositories\Web\RegionRepository;

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


    public static function __getAllRegionsList()
    {
        $listArr = (new RegionRepository())->__getAllRegion();
        $result =  self::get_attr($listArr,'parent_id');
        return $result;
    }


    /**
     * 无限极分类
     *
     * @author 武安<yaobin24@126.com>
     * @param $data
     * @param $pfield
     * @param int $pid
     * @return array
     */
    static public function get_attr($data, $pfield, $pid = 1)
    {
        $tree = array();
        foreach ($data as $v) {
            if ($v[$pfield] == $pid) {
                $v['children'] = self::get_attr($data, $pfield, $v['value']);
                if ($v['children'] == null) {
                    unset($v['children']);
                }
                $tree[] = $v;
            }
        }
        return $tree;
    }
}