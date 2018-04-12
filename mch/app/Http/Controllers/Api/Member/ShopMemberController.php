<?php
/**
 * Created by PhpStorm.
 * User: along
 * Date: 17/9/2
 * Time: 上午11:26
 */
namespace App\Http\Controllers\Api\Member;

use App\Services\Member\ShopMemberService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ShopMemberController extends Controller
{
    protected $shopMemberService;
    public function __construct(ShopMemberService $shopMemberService)
    {
        $this->shopMemberService = $shopMemberService;
    }

    public function getMembers(Request $request){
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['mobile'] = isset($request->mobile) ? $request->mobile : '';

        //等级
        if(isset($request->level)){
            $params['level'] = $request->level;
            $params['level'] = $params['level'] ? $params['level'] : 0;
        }
        $response = $this->shopMemberService->getShopMembers($params);
        return Response::json($response);
    }
}