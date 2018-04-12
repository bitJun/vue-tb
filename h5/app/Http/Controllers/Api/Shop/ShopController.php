<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Services\Shop\ShopService;
use Illuminate\Support\Facades\Response;

class ShopController extends Controller
{
    /**
     * @param $token 用base64编码的, 解密后s=1&a=100, s:店铺ID,a:固定金额,u:收银员ID
     * @param ShopService $shopService
     * @return mixed
     */
    public function getShop($token, ShopService $shopService)
    {
        $token = base64_decode($token);
        parse_str($token, $params);
        $shop = [];
        if($params && isset($params['s'])) {
            $shop = $shopService->getShop($params['s']);
            if($shop && isset($shop['logo']) && $shop['logo']) {
                $shop['logo'] = getImageUrl($shop['logo']);
            }
            if($shop && isset($params['a'])) {
                $shop['amount'] = trim($params['a']);
            }
            if($shop && isset($params['u'])) {
                $shop['uid'] = $params['u'];
            }
        }
        return Response::json($shop);
    }
}
