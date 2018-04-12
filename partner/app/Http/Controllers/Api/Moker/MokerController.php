<?php

namespace App\Http\Controllers\Api\Moker;

use App\Http\Controllers\Controller;
use App\Services\Moker\MokerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class MokerController extends Controller {

    public function getMokerList(Request $request, MokerService $mokerService) {
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['name'] = isset($request->name) ? $request->name : "";
        $params['mobile'] = isset($request->mobile) ? $request->mobile : "";
        $params["partner_id"] = Auth::user()->partner_id;
        $moker_list = $mokerService->getMokerlist($params);
        return Response::json($moker_list);
    }

    public function getMoker($id) {
        $MokerService = new MokerService();
        $params['moker_id'] = isset($id) ? $id : 1;
        $moker_list = $MokerService->getMoker($params);
        return Response::json($moker_list);
    }

}
