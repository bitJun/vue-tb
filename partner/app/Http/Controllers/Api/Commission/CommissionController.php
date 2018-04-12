<?php

namespace App\Http\Controllers\Api\Commission;

use App\Http\Controllers\Controller;
use App\Services\Commission\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller {

    public function getCommissionList(Request $request, CommissionService $CommissionService) {
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['order_sn'] = isset($request->order_sn) ? $request->order_sn : "";
        $params['start'] = isset($request->start) ? $request->start : "";
        $params['end'] = isset($request->end) ? $request->end : "";
        $params['type'] = isset($request->type) ? $request->type : "";
        $params["partner_id"] = Auth::user()->partner_id;
//        $params["partner_id"] = 1;
        $moker_list = $CommissionService->getCommissionlist($params);
        return Response::json($moker_list);
    }

    public function getCommission($id) {
        $CommissionService = new CommissionService();
        $params['id'] = isset($id) ? $id->id : 1;
        $moker_list = $CommissionService->getCommission($params);
        return Response::json($moker_list);
    }

    public function Home_page(CommissionService $CommissionService) {
        $params["partner_id"] = 1;
        $homepage = $CommissionService->homepage($params);
        return Response::json($homepage);
    }

}
