<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Services\Utils\RegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/8
 * Time: 下午3:28
 */
class RegionController extends Controller
{
    public function getRegions(Request $request, RegionService $unitService)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $regions = $unitService->getRegions($parent_id);
        return Response::json($regions);
    }

    public function getRegionsByTree()
    {
        $res = Cache::get('regions:tree');
        if (!$res) {
            $regions = Region::orderBy('listorder')->get();
            foreach ($regions as $_region) {
                $res[$_region->parent_id][] = $_region;
            }
            Cache::forever('shop_regions', $res);
        }

        return $res;
    }


    /**
     * 获取所有地址的无限极分类的信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegionsList()
    {
        $result = Cache::get('regions:list');
        if (!$result) {
            $result = RegionService::__getAllRegionsList();
            Cache::forever('regions:list', $result);
        }
        return response()->json($result);
    }


}