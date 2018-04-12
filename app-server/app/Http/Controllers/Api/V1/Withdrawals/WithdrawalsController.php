<?php

namespace App\Http\Controllers\Api\V1\Withdrawals;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Services\Withdrawals\WithdrawalsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawalsController extends Controller {

    use Helpers;

    /**
     * @SWG\Post(path="/Withdrawals/WithdrawalsAccounts.json",
     *   tags={"Withdrawals"},
     *   summary="申请提现",
     *   description="申请提现",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="alipay_account",
     *     in="query",
     *     description="支付宝帐号",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="alipay_name",
     *     in="query",
     *     description="支付宝真实姓名",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example=
     * {
      "status": true
      }
     * )
     *   )
     * )
     */
    public function Withdrawals_accounts(WithdrawalsService $WithdrawalsService) {
        $Data['alipay_account'] = Request::get('alipay_account');
        $Data['alipay_name'] = Request::get('alipay_name');
        $Data['moker_id'] = Auth::user()->id;
        $res = $WithdrawalsService->postAccounts($Data);
        return $this->response->array(['status' => $res]);
    }

    /**
     * @SWG\Post(path="/Withdrawals/MokerWithdraw.json",
     *   tags={"Withdrawals"},
     *   summary="申请提现",
     *   description="申请提现",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="amount",
     *     in="query",
     *     description="提现金额",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example=
     * {
      "status": true
      }
     * )
     *   )
     * )
     */
    public function MokerWithdraw(WithdrawalsService $WithdrawalsService) {
        $Data['amount'] = Request::get('amount');
        $Data['moker_id'] = Auth::user()->id;
        $res = $WithdrawalsService->postRecord($Data);
        if ($Data['amount'] < 0.1) {
            return $this->response->errorBadRequest('提现金额不能小于0.1');
        }
        if (!$res) {
            return $this->response->errorBadRequest('提现申请失败!');
        }
        return $this->response->array(['status' => $res]);
    }

    /**
     * @SWG\get(path="/Withdrawals/WithdrawRecord.json",
     *   tags={"Withdrawals"},
     *   summary="提现记录",
     *   description="提现记录",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="偏移量",
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     description="每页条数",
     *     type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功(0:申请中；3：成功；4：失败)",
     *      @SWG\Schema(example=
     * {
      "moker_withdraws": {
      {
      "id": 1,
      "moker_id": 1,
      "amount": "520.00",
      "status": 0,
      "created_at": "2017-11-30 10:13:20",
      "updated_at": "2017-11-30 10:13:20"
      },
      {
      "id": 2,
      "moker_id": 1,
      "amount": "520.00",
      "status": 0,
      "created_at": "2017-11-30 10:13:52",
      "updated_at": "2017-11-30 10:13:52"
      }
      }
      }
     * 
     * )
     *   )
     * )
     */
    public function WithdrawRecord(WithdrawalsService $WithdrawalsService) {
        $Data['moker_id'] = Auth::user()->id;
        $Data['offset'] = Request::get('offset');
        $Data['limit'] = Request::get('limit');
        $res = $WithdrawalsService->getRecord($Data);
        return $res;
    }

    /**
     * @SWG\get(path="/Withdrawals/getaccounts.json",
     *   tags={"Withdrawals"},
     *   summary="获取提现帐号",
     *   description="获取提现帐号",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example=
     * {
      "moker_cash_account": {
      "id": 1,
      "moker_id": 1,
      "alipay_account": "asdasdasdas",
      "alipay_name": "asdasdasd",
      "created_at": "2017-11-29 17:59:37",
      "updated_at": "2017-11-29 17:59:37"
      }
      }
     * )
     *   )
     * )
     */
    public function getAccounts(WithdrawalsService $WithdrawalsService) {
        $Data['moker_id'] = Auth::user()->id;
        $res = $WithdrawalsService->getAccounts($Data);
        return $res;
    }

    /**
     * @SWG\put(path="/Withdrawals/accounts.json",
     *   tags={"Withdrawals"},
     *   summary="修改提现帐号",
     *   description="修改提现帐号",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="alipay_account",
     *     in="query",
     *     description="支付宝帐号",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="alipay_name",
     *     in="query",
     *     description="支付宝真实姓名",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example=
     * {
      "status": true
      }
     * )
     *   )
     * )
     */
    public function putAccounts(WithdrawalsService $WithdrawalsService) {
        $moker_id = Auth::user()->id;
        $Data['alipay_name'] = Request::get('alipay_name');
        $Data['alipay_account'] = Request::get('alipay_account');
        $res = $WithdrawalsService->putAccounts($moker_id, $Data);
        return $this->response->array(['status' => $res]);
    }

    /**
     * @SWG\put(path="/Withdrawals/examine.json",
     *   tags={"Withdrawals"},
     *   summary="审核提现记录",
     *   description="审核提现记录",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="提现记录id",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="status",
     *     in="query",
     *     description="状态码（1：通过；2：拒绝）",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example=
     * {
      "status": true
      }
     * )
     *   )
     * )
     */
    public function putExamine(WithdrawalsService $WithdrawalsService) {
        $moker_id = Auth::user()->id;
        $Data['id'] = Request::get('id');
        $Data['status'] = Request::get('status');
        $Data['moker_id'] = $moker_id;
        $res = $WithdrawalsService->putExamine($Data);
        return $this->response->array(['status' => $res]);
    }

}
