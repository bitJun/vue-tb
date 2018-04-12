<?php
namespace App\Http\Controllers\Api\Order;


use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class OrderController extends Controller
{
    protected $shop_id = 0;
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return mixed
     * 订单列表
     */
    public function getOrders(Request $request){
        $shop_id = Auth::user()->shop_id;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['limit'] = isset($request->limit) ? $request->limit : 20;
        $params['order_sn'] = isset($request->order_sn) ? $request->order_sn : '';
        $params['mobile'] = isset($request->mobile) ? $request->mobile : '';
        $params['status'] = isset($request->status) ? $request->status : '';
        $params['type'] = isset($request->type) ? $request->type : '';
        $params['shop_id'] = $shop_id;
        $orderService = new OrderService($shop_id);
        $response = $orderService->getOrders($params);
        return Response::json($response);
    }

    /**
     * @param Request $request
     * @return mixed
     * 订单详情
     */
    public function getOrder($order_id){
        $shop_id = Auth::user()->shop_id;
        $params['order_id'] = $order_id;
        $params['shop_id'] = $shop_id;
        $orderService = new OrderService($shop_id);
        $order = $orderService->getOrder($params);
        return Response::json($order);
    }

}