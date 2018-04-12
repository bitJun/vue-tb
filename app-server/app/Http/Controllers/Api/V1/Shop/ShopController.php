<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/11/24
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Api\V1\Shop;

use App\Http\Controllers\Controller;
use App\Model\MokerShop;
use App\Model\Shop;
use App\Model\ShopApply;
use App\Services\Moker\MokerCommService;
use App\Services\Shop\ShopService;
use App\Transformers\MokerShopTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    use Helpers;

    /**
     * @SWG\Post(path="/shop.json",
     *   tags={"shop"},
     *   summary="添加店铺",
     *   description="添加店铺",
     *   operationId="postShop",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="company",
     *      description="名称",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="logo",
     *      description="logo",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="province",
     *      description="省",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="city",
     *      description="市",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="district",
     *      description="区",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="address",
     *      description="详细地址",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="num_license",
     *      description="营业执照",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="contact",
     *      description="联系人",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="mobile",
     *      description="联系电话",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="tag_id",
     *      description="行业",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="imgs",
     *      description="店铺图片",
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="description",
     *      description="店铺简介",
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"status":true}
     *      )
     *   )
     * )
     */
    public function postShop(Request $request,ShopService $shopService)
    {
        $rules = [
            'company' => 'required',
            'logo' => 'required',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'district' => 'required|integer',
            'address' => 'required',
            'tag_id'=>'required|integer',
            'num_license' => 'required',
            'contact' => 'required',
            'mobile' => 'required|digits_between:6,20'
        ];
        $message = [
            'company.required'=>'请填写名称',
            'logo.required'=>'请上传LOGO',
            'province.required'=>'请选择省',
            'province.integer'=>'省类型不正确',
            'city.required'=>'请选择市',
            'city.integer'=>'市类型不正确',
            'district.required'=>'请选择区',
            'district.integer'=>'区类型不正确',
            'address.required'=>'请填写详细地址',
            'tag_id.required'=>'请选择行业类型',
            'tag_id.integer'=>'行业类型只支持正整数类型',
            'num_license.required'=>'请上传营业执照',
            'contact.required'=>'请填写联系人',
            'mobile.required'=>'请填写手机号码',
            'mobile.digits_between'=>'手机号码格式不正确',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            $this->response->errorBadRequest($validator->errors()->first());
        }
        $shopService->postShop($request->all());
        return $this->response->array(['status'=>true])->setStatusCode(200);
    }


    /**
     * @SWG\Get(path="/shops.json",
     *   tags={"shop"},
     *   summary="店铺列表",
     *   description="店铺列表",
     *   operationId="getShops",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLm1va2VyLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNTExOTI0NDIwLCJuYmYiOjE1MTE5MjQ0MjAsImp0aSI6ImNTeVBSbFBHOW1BYm05MkkiLCJzdWIiOjEsInBydiI6Ijg0Mzg4YTkzOWE3OGE1ZDlmYzVlNTE3NmM2MjM5Y2U0NGFkZTZmZTcifQ.CJcZqy0BY8leArhFCsW5X8LUPaB3mqtNpnRmcNWH7LQ"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      description="类型 nopass：未审核或审核失败 ok:审核通过的店铺（默认）",
     *      required=false,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="limit",
     *      description="每页条数",
     *      required=false,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          "data" = {
     *              {
     *                   "id":1,
     *                   "shop_apply_id",
     *                   "shop_name": "魔店示范店",
     *                   "shop_logo": "",
     *                   "shop_id": 1,
     *                   "status": "0:审核中 1:通过 2:拒绝",
     *                   "today_comm": "0.54",
     *                   "total_comm": "1000.54",
     *                   "created_at":"2017-11-25  16:22:37"
     *               },
     *          },
     *          "meta"={
     *               "stay_shop_count":3,
     *               "fail_shop_count":0,
     *               "pagination"={
     *                  "total": 5,
     *                   "count": 5,
     *                   "per_page": 5,
     *                   "current_page": 1,
     *                   "total_pages": 1,
     *                   "links": ""
     *               }
     *           }
     *      })
     *   )
     * )
     */

    public function getShops(Request $request,ShopService $shopService){
        $params = [];
        //类型 0:审核中 1:通过 2:拒绝
        $params['type'] = (isset($request->type) && $request->type=='nopass') ? 'nopass' :'ok';
        $params['moker_id'] = Auth::user()->id;
        $params['limit'] = isset($request->limit) ? $request->limit : 10;

        $data = $shopService->getMokerShops($params);
        if ($data) {
            $shopModel = new Shop();
            $shopApplyModel = new ShopApply();
            $mokerCommService = new MokerCommService();
            foreach ($data as &$_data){
                //正式店铺
                if($_data['shop_id']) {
                    //店铺信息
                    $shop = $shopModel->where('id',$_data['shop_id'])->first();
                    $_data['shop_name'] = $shop['name'];
                    $_data['shop_logo'] = $shop['logo'];

                }else{
                    $shop = $shopApplyModel->where('id',$_data['shop_apply_id'])->first();
                    $_data['shop_name'] = $shop['company'];
                    $_data['shop_logo'] = $shop['logo'];
                }

                $_data['today_comm'] = $_data['total_comm'] = 0.00
                ;
                if($_data['status'] == 1 && $_data['shop_id']) {
                    //今日收益
                    $_data['today_comm'] = $mokerCommService->getCommSum(['moker_id'=>$params['moker_id'],'shop_id'=>$_data['shop_id'],'type'=>0,'source'=>'today']);
                    //累计收益
                    $_data['total_comm'] = $mokerCommService->getCommSum(['moker_id'=>$params['moker_id'],'shop_id'=>$_data['shop_id'],'type'=>0]);
                }
            }

        }

        if ($params['type'] == 'ok') {
            //待审核通过店铺数量
            $stay_shop_count = MokerShop::where('moker_id',$params['moker_id'])->where('status',0)->count();
            //未通过审核店铺数量
            $fail_shop_count = MokerShop::where('moker_id',$params['moker_id'])->where('status',2)->count();
            return $this->response->paginator($data, new MokerShopTransformer())->addMeta('stay_shop_count',$stay_shop_count)->addMeta('fail_shop_count',$fail_shop_count);

        }else{
            return $this->response->paginator($data, new MokerShopTransformer());
        }

    }

    /**
     * @SWG\Get(path="/shop/1.json",
     *   tags={"shop"},
     *   summary="店铺详情",
     *   description="取得店铺详情",
     *   operationId="getShop",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLm1va2VyLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNTExOTI0NDIwLCJuYmYiOjE1MTE5MjQ0MjAsImp0aSI6ImNTeVBSbFBHOW1BYm05MkkiLCJzdWIiOjEsInBydiI6Ijg0Mzg4YTkzOWE3OGE1ZDlmYzVlNTE3NmM2MjM5Y2U0NGFkZTZmZTcifQ.CJcZqy0BY8leArhFCsW5X8LUPaB3mqtNpnRmcNWH7LQ"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *              "id": 1,
     *              "moker_id": 1,
     *              "shop_id": 1,
     *              "shop_apply_id": 55,
     *              "comm_percent": "0.20",
     *              "enabled": 1,
     *              "status": 1,
     *              "created_at": "2017-11-25 16:22:37",
     *              "updated_at": "2017-11-25 16:22:37",
     *              "shop_name": "菲来特",
     *              "shop_logo": "http://oth9z8cjj.bkt.clouddn.com/1.jpg",
     *              "num_license": "http://oth9z8cjj.bkt.clouddn.com/2.jpg",
     *              "imgs": {
     *                  "http://oth9z8cjj.bkt.clouddn.com/3.jpg"
     *                  },
     *              "description": "test",
     *              "mobile": "155558105636",
     *              "contact": "小白",
     *              "province": 330000,
     *              "city": 330100,
     *              "district": 330106,
     *              "address": "7号"
     *      })
     *   )
     * )
     */
    public function getShop($id,ShopService $shopService){
        $shop = $shopService->getMokerShop($id);
        if(!$shop){
            return [];
        }
        return $this->response->array($shop);
    }

    /**
     * @SWG\Get(path="/shop_apply/1.json",
     *   tags={"shop"},
     *   summary="店铺详情(未审核通过)",
     *   description="取得店铺详情",
     *   operationId="getShopApply",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLm1va2VyLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNTExOTI0NDIwLCJuYmYiOjE1MTE5MjQ0MjAsImp0aSI6ImNTeVBSbFBHOW1BYm05MkkiLCJzdWIiOjEsInBydiI6Ijg0Mzg4YTkzOWE3OGE1ZDlmYzVlNTE3NmM2MjM5Y2U0NGFkZTZmZTcifQ.CJcZqy0BY8leArhFCsW5X8LUPaB3mqtNpnRmcNWH7LQ"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *              "id": 1,
     *              "company":"吉利",
     *              "logo": "1.jpg",
     *              "contact": "小李",
     *              "mobile": "15558105636",
     *              "tag_id": "2",
     *              "tag_str": "餐饮",
     *              "province": 330000,
     *              "city": 330100,
     *              "district": 330106,
     *              "province_str": "浙江省",
     *              "city_str": "杭州市",
     *              "district_str": "西湖区",
     *              "address": "7号",
     *              "num_license": "2.jpg",
     *              "description": "这是简介",
     *              "imgs": {
     *                  "3.jpg"
     *                  },
     *              "created_at": "2017-11-25 16:22:37",
     *              "updated_at": "2017-11-25 16:22:37"
     *      })
     *   )
     * )
     */
    public function getShopApply($id,ShopService $shopService)
    {
        $data = $shopService->getShopApply($id);
        return $this->response->array($data);
    }

    /**
     * @SWG\Put(path="/shop_apply/1.json",
     *   tags={"shop"},
     *   summary="修改未审核店铺",
     *   description="修改未审核店铺",
     *   operationId="putShopApply",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="company",
     *      description="名称",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="logo",
     *      description="logo",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="province",
     *      description="省",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="city",
     *      description="市",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="district",
     *      description="区",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="address",
     *      description="详细地址",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="num_license",
     *      description="营业执照",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="contact",
     *      description="联系人",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="mobile",
     *      description="联系电话",
     *      required=true,
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="tag_id",
     *      description="行业",
     *      required=true,
     *      in="query",
     *      type="integer"
     *   ),
     *   @SWG\Parameter(
     *      name="imgs",
     *      description="店铺图片",
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Parameter(
     *      name="description",
     *      description="店铺简介",
     *      in="query",
     *      type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"status":true}
     *      )
     *   )
     * )
     */
    public function putShopApply($id,Request $request,ShopService $shopService)
    {
        $rules = [
            'company' => 'required',
            'logo' => 'required',
            'province' => 'required|integer',
            'city' => 'required|integer',
            //'district' => 'required|integer',
            'address' => 'required',
            'tag_id'=>'required|integer',
            'num_license' => 'required',
            'contact' => 'required',
            'mobile' => 'required|digits_between:6,20'
        ];
        $message = [
            'company.required'=>'请填写名称',
            'logo.required'=>'请上传LOGO',
            'province.required'=>'请选择省',
            'province.integer'=>'省类型不正确',
            'city.required'=>'请选择市',
            'city.integer'=>'市类型不正确',
            //'district.required'=>'请选择区',
            //'district.integer'=>'区类型不正确',
            'address.required'=>'请填写详细地址',
            'tag_id.required'=>'请选择行业类型',
            'tag_id.integer'=>'行业类型只支持正整数类型',
            'num_license.required'=>'请上传营业执照',
            'contact.required'=>'请填写联系人',
            'mobile.required'=>'请填写手机号码',
            'mobile.digits_between'=>'手机号码格式不正确',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            $this->response->errorBadRequest($validator->errors()->first());
        }
        $result = $shopService->putShopApply($id,$request->all());
        if(!$result['status'])
        {
            $this->response->errorBadRequest($result['message']);
        }
        return $this->response->array(['status'=>true])->setStatusCode(200);
    }

    public function auditShop($id,Request $request,ShopService $shopService)
    {
        $rules = [
            'mobile' => 'required|digits_between:6,20'
        ];
        $message = [
            'mobile.required'=>'请填写手机号码',
            'mobile.digits_between'=>'手机号码格式不正确',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if($validator->fails())
        {
            $this->response->errorBadRequest($validator->errors()->first());
        }
        $shopService->auditShop($id,$request->all());
        return $this->response->array(['status'=>true])->setStatusCode(200);
    }
}