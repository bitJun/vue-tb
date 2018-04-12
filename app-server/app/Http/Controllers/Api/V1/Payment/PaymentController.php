<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/12/6
 * Time: 下午1:46
 */

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Dingo\Api\Routing\Helpers;

class PaymentController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/payments.json",
     *   tags={"order"},
     *   summary="支付方式列表",
     *   description="支付方式列表",
     *   operationId="edit",
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
     *      @SWG\Property(example={"payments":{{"id":1,"name":"\u652f\u4ed8\u5b9d","logo":"http:\/\/oth9z8cjj.bkt.clouddn.com\/%E6%94%AF%E4%BB%98%E5%AE%9D@3x.png","code":"alipay"},{"id":2,"name":"\u5fae\u4fe1\u652f\u4ed8","logo":null,"code":"llpay"}}}),
     *   )
     * )
     */
    public function getPayments(PaymentService $paymentService){
        $data = $paymentService->getPayments();
        return $this->response->array($data);
    }

}