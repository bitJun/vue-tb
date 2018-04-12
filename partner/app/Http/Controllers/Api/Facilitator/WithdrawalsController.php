<?php

namespace App\Http\Controllers\Api\Facilitator;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Services\Partner\WithdrawalsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class WithdrawalsController extends Controller {

    public function Withdrawals_accounts(WithdrawalsService $WithdrawalsService) {
        $Data['bank_account'] = Request::get('bank_account');
        $Data['bank_name'] = Request::get('bank_name');
        $Data['brabank_name'] = Request::get('brabank_name');
        $Data['bank_code'] = Request::get('bank_code');
        $Data['bank_no'] = Request::get('bank_no');
        $Data['bank_mobile'] = Request::get('bank_mobile');
        $Data['bank_province'] = Request::get('bank_province');
        $Data['bank_city'] = Request::get('bank_city');
        $Data['bank_district'] = Request::get('bank_district');
        $Data['partner_id'] = Auth::user()->partner_id;
        $res = $WithdrawalsService->postAccounts($Data);
        return Response::json($res);
    }

    public function MokerWithdraw(WithdrawalsService $WithdrawalsService) {
        $Data['amount'] = Request::get('amount');
        $Data['partner_id'] = Auth::user()->partner_id;
        $res = $WithdrawalsService->postRecord($Data);
        if ($Data['amount'] < 0.1) {
            return $this->response->errorBadRequest('提现金额不能小于0.1');
        }
        if (!$res) {
            return $this->response->errorBadRequest('提现申请失败!');
        }
        return $this->response->array(['status' => $res]);
    }

    public function WithdrawRecord(WithdrawalsService $WithdrawalsService) {
        $Data['partner_id'] = Auth::user()->partner_id;
        $Data['offset'] = Request::get('offset');
        $Data['limit'] = Request::get('limit');
        $res = $WithdrawalsService->getRecord($Data);
        return $res;
    }

    public function getAccounts(WithdrawalsService $WithdrawalsService) {
        $Data['partner_id'] = Auth::user()->partner_id;
        $res = $WithdrawalsService->getAccounts($Data);
        return $res;
    }

    public function putAccounts(WithdrawalsService $WithdrawalsService) {
        $partner_id = Auth::user()->partner_id;
        $Data['bank_account'] = Request::get('bank_account');
        $Data['bank_name'] = Request::get('bank_name');
        $Data['bank_code'] = Request::get('bank_code');
        $Data['brabank_name'] = Request::get('brabank_name');
        $Data['bank_mobile'] = Request::get('bank_mobile');
        $Data['bank_province'] = Request::get('bank_province');
        $Data['bank_city'] = Request::get('bank_city');
        $Data['bank_district'] = Request::get('bank_district');
        $res = $WithdrawalsService->putAccounts($partner_id, $Data);
        return $res;
    }

    public function getBank(WithdrawalsService $WithdrawalsService) {
        $res = $WithdrawalsService->getBank();
        return $res;
    }

}
