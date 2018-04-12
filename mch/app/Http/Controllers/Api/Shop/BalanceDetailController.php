<?php

namespace App\Http\Controllers\Api\Shop;

use App\Services\Shop\BalanceDetailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class BalanceDetailController extends Controller
{
    public function getBalanceDetails(Request $request)
    {
        $balanceDetailService = new BalanceDetailService();
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['order_sn'] = isset($request->order_sn) ? $request->order_sn : '';
        $params['date_start'] = isset($request->date_start) ? $request->date_start : '';
        $params['date_end'] = isset($request->date_end) ? $request->date_end : '';
        $params['type'] = isset($request->type) ? $request->type : 'all';
        $response = $balanceDetailService->getBalanceDetails($params);
        return Response::json($response);
    }
}
