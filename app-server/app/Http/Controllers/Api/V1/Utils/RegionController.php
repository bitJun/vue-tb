<?php

namespace App\Http\Controllers\Api\V1\Utils;
use App\Http\Controllers\Controller;
use App\Model\Region;
use App\Services\Utils\RegionService;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/8
 * Time: 下午3:28
 */
class RegionController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/regions.json",
     *   tags={"utils"},
     *   summary="获取省市列表",
     *   description="获取省市列表",
     *   operationId="getRegions",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="parent_id",
     *     in="query",
     *     description="上级id",
     *     required=false,
     *     type="integer",
     *     default=0
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *      "data" = {
     *           {
     *           "id": 110000,
     *           "name": "北京",
     *           "parent_id": 1,
     *           "listorder": 1
     *           },
     *           {
     *           "id": 120000,
     *           "name": "天津",
     *           "parent_id": 1,
     *           "listorder": 2
     *           }
     *     }
     *      }),
     *   )
     * )
     */
    public function getRegions(Request $request, RegionService $unitService)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : 0;
        $regions = $unitService->getRegions($parent_id);
        return $this->response->array(["data"=>$regions]);
    }

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