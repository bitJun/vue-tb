<?php

namespace App\Http\Controllers\Api\V1\Moker;

use App\Model\MokerLevel;
use App\Services\Moker\MokerBindService;
use App\Services\Moker\MokerService;
use App\Http\Controllers\Controller;
use App\Transformers\MokerTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MokerController extends Controller
{
    use Helpers;

    /**
     * @SWG\Put(path="/moker.json",
     *   tags={"moker"},
     *   summary="个人资料编辑接口",
     *   description="个人资料编辑接口",
     *   operationId="edit",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="name",
     *     in="query",
     *     description="昵称",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="avatar",
     *     in="query",
     *     description="头像",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="gender",
     *     in="query",
     *     description="性别 0:未知,1:男,2:女",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="birthday",
     *     in="query",
     *     description="生日",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="province_id",
     *     in="query",
     *     description="省ID",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="city_id",
     *     in="query",
     *     description="市ID",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="district_id",
     *     in="query",
     *     description="区ID",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(ref="#/definitions/Moker")
     *   )
     * )
     */
    public function editMoker(MokerService $mokerService)
    {
        $data = Request::all();
        $rules = [
            'gender' => 'in:0,1,2'
        ];
        $message = [
            'gender.in'=>'性别参数值非法',
        ];
        $validator = Validator::make($data, $rules, $message);
        if($validator->fails())
        {
            $this->response->errorBadRequest($validator->errors()->first());
        }
        $editData = [];
        isset($data['name']) && $editData['name'] = $data['name'];
        isset($data['avatar']) && $editData['avatar'] = $data['avatar'];
        isset($data['gender']) && $editData['gender'] = $data['gender'];
        isset($data['birthday']) && $editData['birthday'] = $data['birthday'];
        isset($data['province_id']) && $editData['province_id'] = $data['province_id'];
        isset($data['city_id']) && $editData['city_id'] = $data['city_id'];
        isset($data['district_id']) && $editData['district_id'] = $data['district_id'];
        if($editData) {
            $mokerService->putMoker(Auth::user()->id, $editData);
        }
        $moker = $mokerService->getById(Auth::user()->id);
        $mt = new MokerTransformer();
        $resp = $mt->transform($moker);
        return $this->response->array($resp);
    }

    /**
     * @SWG\Post(path="/moker/bind.json",
     *   tags={"moker"},
     *   summary="个人中心微信绑定接口",
     *   description="个人中心微信绑定接口",
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
     *     name="openid",
     *     in="query",
     *     description="微信openid",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="unionid",
     *     in="query",
     *     description="微信unionid",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="access_token",
     *     in="query",
     *     description="微信access_token",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="expires_in",
     *     in="query",
     *     description="微信expires_in",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="refresh_token",
     *     in="query",
     *     description="refresh_token",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[openid]",
     *     in="query",
     *     description="微信获取的用户信息openid",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[nickname]",
     *     in="query",
     *     description="微信获取的用户信息nickname",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[sex]",
     *     in="query",
     *     description="微信获取的用户信息sex",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[province]",
     *     in="query",
     *     description="微信获取的用户信息province",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[city]",
     *     in="query",
     *     description="微信获取的用户信息city",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[country]",
     *     in="query",
     *     description="微信获取的用户信息country",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[headimgurl]",
     *     in="query",
     *     description="微信获取的用户信息headimgurl",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[privilege]",
     *     in="query",
     *     description="微信获取的用户信息privilege",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="user_info[unionid]",
     *     in="query",
     *     description="微信获取的用户信息unionid",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(ref="#/definitions/Moker")
     *   )
     * )
     */
    public function bind(MokerService $mokerService, MokerBindService $mokerBindService)
    {
        $bindData['moker_id'] = Auth::user()->id;
        $bindData['type'] = Request::get('type') ? Request::get('type') : 0;
        $bindData['openid'] = Request::get('openid');
        $bindData['unionid'] = Request::get('unionid');
        $bindData['access_token'] = Request::get('access_token');
        $bindData['expires_in'] = Request::get('expires_in');
        $bindData['refresh_token'] = Request::get('refresh_token');
        $bindData['user_info'] = $userInfo = Request::get('user_info');
        $mokerBind = $mokerBindService->getBindByOpenid($bindData['openid']);
        if($mokerBind) {
            $this->response->errorBadRequest('该微信已经绑定其他用户');
        }
        if($mokerBind = $mokerBindService->create($bindData)) {
            $mokerData = [];
            $mokerData['name'] = isset($userInfo['nickname']) ? $userInfo['nickname'] : '';
            $mokerData['avatar'] = isset($userInfo['headimgurl']) ? $userInfo['headimgurl'] : '';
            $mokerData['gender'] = isset($userInfo['sex']) ? $userInfo['sex'] : 0;

            if($mokerData) {
                $mokerService->putMoker(Auth::user()->id, $mokerData);
            }
            $moker = $mokerService->getById(Auth::user()->id);
            $mt = new MokerTransformer();
            $resp = $mt->transform($moker);
            return $this->response->array($resp);
        } else {
            $this->response->errorInternal('绑定失败');
        }
    }

    /**
     * @SWG\Post(path="/moker.json",
     *   tags={"moker"},
     *   summary="注册魔客",
     *   description="注册魔客",
     *   operationId="postMoker",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     description="密码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="province_id",
     *     in="query",
     *     description="省",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="city_id",
     *     in="query",
     *     description="市",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="district_id",
     *     in="query",
     *     description="区",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="code",
     *     in="query",
     *     description="短信验证码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="captcha",
     *     in="query",
     *     description="图片验证码",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="invitee_moker_id",
     *     in="query",
     *     description="邀请魔客ID",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Schema(example={
        "moker": {
        "id": 1,
        "mobile": "13968154713",
        "password": "$2y$10$udYz9OeAUKPEAVrJuvOgyeToTdA43XdJ44MrDGoieGNXBFP3fAwM2",
        "name": "李白",
        "avatar": null,
        "gender": 1,
        "birthday": "2008-11-06",
        "balance": "1000.54",
        "status": 1,
        "device_id": "f421bc7294c44d4c90e691119beaa4fc",
        "device_type": 0,
        "level_id": 2,
        "province_id": 0,
        "city_id": 0,
        "district_id": 0,
        "created_at": "2017-11-22 16:01:16",
        "updated_at": "2017-11-29 17:13:06"
        }
        })
     *   )
     * )
     */
    public function postMoker(MokerService $mokerService)
    {
        $rules = [
            'mobile'=>'required|digits_between:6,20|unique:moker',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
            'province_id'=>'required',
            'city_id'=>'required',
            'district_id' => 'required',
            'code' => 'required',
            'captcha'=>'captcha',
        ];
        $message = [
            'mobile.required'=>'请填写手机号',
            'mobile.digits_between'=>'手机号格式不正确',
            'mobile.unique'=>'该手机号已加入魔客，请换一个手机号码',
            'password.required'=>'请填写密码',
            'password.regex'=>'密码至少6位，且必须包含大写字母、小写字母和数字',
            'province_id.required'=>'请选择所属省',
            'city_id.required'=>'请选择所属市',
            'district_id.required'=>'请选把所属区',
            'code.required'=>'请填写手机验证码',
            'captcha.captcha'=>'图片验证码不正确',
        ];
        $validator = Validator::make(Request::all(), $rules, $message);
        if($validator->fails())
        {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        $mobile = Request::get('mobile');
        $cacheCode = Redis::get('moker_app_code:' . $mobile);
        if (!$cacheCode || $cacheCode != Request::get('code')) {
            $this->response->errorBadRequest('短信验证码错误');
        }

        $res = [];
        $data = $mokerService->postMoker(Request::all());
        if($data)
        {
            $res = [
                'id'=>$data['id'],
                'mobile'=>$data['mobile'],
            ];
            Session::put('mk_res',$res);
        }
        return $this->response->array($res);
    }
     /**
     * @SWG\get(path="/moker.json",
     *   tags={"moker"},
     *   summary="个人中心获取moker信息",
     *   description="个人中心获取moker信息",
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
     *      description="请求成功（name：魔客姓名；mobile：手机号码；avatar：头像）",
     *      @SWG\Schema(example=
      {
      "moker": {
      "id": 1,
      "mobile": "13968154713",
      "password": "$2y$10$udYz9OeAUKPEAVrJuvOgyeToTdA43XdJ44MrDGoieGNXBFP3fAwM2",
      "name": "李白",
      "avatar": null,
      "gender": 1,
      "birthday": "2008-11-06",
      "balance": "1000.54",
      "status": 1,
      "device_id": "f421bc7294c44d4c90e691119beaa4fc",
      "device_type": 0,
      "level_id": 2,
      "province_id": 0,
      "city_id": 0,
      "district_id": 0,
      "created_at": "2017-11-22 16:01:16",
      "updated_at": "2017-11-29 17:13:06"
      }
      }

      )
     *   )
     * )
     */
    public function getMoker(MokerService $mokerService) {
        $moker_id = Auth::user()->id;
        $res = $mokerService->getmoker($moker_id);
        return $res;
    }

    /**
     * @SWG\get(path="/invitee.json",
     *   tags={"moker"},
     *   summary="获取邀请魔客",
     *   description="获取邀请魔客",
     *   operationId="bind",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="status",
     *     in="query",
     *     description="是否加入（0:未加入 1:已加入）",
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
     *      description="请求成功（number:邀请魔客人数；total：累积收益）",
     *      @SWG\Schema(example=
     {
  "number": 1,
  "total": 1000,
  "data": {
    {
      "comm": 0,
      "created_at": "2017-12-11 10:10:01",
      "avatar": "",
      "status": "0",
      "name": "155****6665"
    }
  }
}

      )
     *   )
     * )
     */
    public function getInvitee(MokerService $mokerService) {
        $moker_id = Auth::user()->id;
        $offset = Request::get('offset') ? intval(Request::get('offset')) : 0;
        $limit = Request::get('limit') ? intval(Request::get('limit')) : 10;
        $status = Request::get('status');
        $res = $mokerService->getInvitee($moker_id, $status, $offset, $limit);
        return $res;
    }

    /**
     * @SWG\Get(path="/invitation/info.json",
     *   tags={"moker"},
     *   summary="获取魔客邀请展示页面信息",
     *   description="获取魔客邀请展示页面信息",
     *   operationId="getInvitationInfo",
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
     *      @SWG\Property(
     *          example={ "invitation_qrcode": "iVBORw0KGgoAAAANSUhEUgAAAPAAAADwCAIAAACxN37FAAAEv0lEQVR4nO3dS27cOhQAUdt4+1+ykUFmDzJAt8RPV84Zu6V2XNHgghQ/v7+/P6Dia/cXgCcJmhRBkyJoUgRNiqBJETQpgiZF0KQImhRBkyJoUgRNiqBJETQpgiZF0KT899rHvr6O+58wvvXm8ssv27mz95/u8td867/m/xz3m8AdgiZF0KQImhRBkyJoUl4c2106cPJ1cyA1/vHx333ZePHm737gX3Poag9eC7YTNCmCJkXQpAiaFEGT8uTY7tLe4dH44rIZE7rxuduMn5wxdzt/FOgJTYqgSRE0KYImRdCkCJqU6WO7vW4O4/ZOqWbM8vI8oUkRNCmCJkXQpAiaFEGTEh/bLVsZd9OylXH5WZ4nNCmCJkXQpAiaFEGTImhSpo/t9s6JZoyuZszybq4KfOvx4rM8oUkRNCmCJkXQpAiaFEGT8uTY7sADSWfY+8a6ZWvo3vSv+ZZfGn4iaFIETYqgSRE0KYIm5fP89VOnmfG+vN75sLt4QpMiaFIETYqgSRE0KYIm5cXVdsuWYi3b/jl+9/GfnLFz9sBlfUctFfSEJkXQpAiaFEGTImhSBE3Ki6vtbq44G7/mjMNYLy1bhtabeC47MHfoane+CpxG0KQImhRBkyJoUgRNypNju717QvceGrt36jdjEdy4ozbeekKTImhSBE2KoEkRNCmCJmX6SbKX3mUcdmnZfHD80NhljlpYd32LB68F2wmaFEGTImhSBE2KoEmZPrbbe8jp3pfTzbj7jCWNpZ3IntCkCJoUQZMiaFIETYqgSXnyJNkZ+2EP/Pi7vB5umaPOqfCEJkXQpAiaFEGTImhSBE3K9HfbvcvHL8245ri9s7zz98Ne33f2DWAlQZMiaFIETYqgSRE0KU+utru0bHj0LqdkLLv7za80/vFx3m0HvyNoUgRNiqBJETQpgiZlz9juwHVkB57QemnBPtNHWG0HDxA0KYImRdCkCJoUQZPy4pEUy1acHTiQWnbUw6Xxf/m96/LGebcd/EjQpAiaFEGTImhSBE3KkyfJLjvkdO+mzps32nsM7t639V2ySRZ+JGhSBE2KoEkRNCmCJuWgIynGLVttt/d02mVndCzj3XbwO4ImRdCkCJoUQZMiaFKmj+1mbKe9ae8KvnFvvVxu12TWE5oUQZMiaFIETYqgSRE0KdOPpNjrwPe7vcvUb/yaMxjbwceHoIkRNCmCJkXQpAialOlHUiyzbBx2c5Z34DRt2R5bJ8nC7wiaFEGTImhSBE2KoEmZfiTFDHs33i47p2LvruEDrznCE5oUQZMiaFIETYqgSRE0KU+O7S7t3VK6bBx286CJZedU5N8V6AlNiqBJETQpgiZF0KQImpTpY7sD3RyHzfjJZXtsD9xK/OwWXU9oUgRNiqBJETQpgiZF0KT8i2O7ccv2w45fc8Yaur0bb622gx8JmhRBkyJoUgRNiqBJmT62651Uu/eNdTM2yc6YOe5y+veDXxE0KYImRdCkCJoUQZPy5Nju/JnOX3s3io7/5Iz9sAu2qc675tB9Z98AVhI0KYImRdCkCJoUQZPy2VsNx7/ME5oUQZMiaFIETYqgSRE0KYImRdCkCJoUQZMiaFIETYqgSRE0KYImRdCkCJoUQZPyB9qkSn3jtcr6AAAAAElFTkSuQmCC", "comm": "1000.00", "invitation_url": "http://api.moker.com/invitation/1", "title": "好友邀您加入魔客", "description": "您的好友【李白】邀请您加入魔客，轻松赚尽360行，让每一次分享创造价值！", "logo": "http://oth9z8cjj.bkt.clouddn.com/moker/logo.png" }
     *      )
     *   )
     * )
     */
    public function getInvitationInfo()
    {
        $inviterId = Auth::user()->id;
        $url = env('APP_URL') . '/invitation/' . $inviterId;
        $cacheKey = 'moker.invitation_qrcode'.$inviterId;
        if((!$invitationQrcode = Cache::get($cacheKey))) {
            $invitationQr = QrCode::format('png');
            $invitationContent = $invitationQr->size(240)->margin(1)->generate($url);
            $invitationQrcode = base64_encode($invitationContent);
            Cache::set($cacheKey, $invitationQrcode);
        }
        $res['invitation_qrcode'] = $invitationQrcode;
        $res['comm'] = MokerLevel::where('id', 2)->value('parent_comm');
        $res['invitation_url'] = $url;
        $res['title'] = '好友邀您加入魔客';
        $name = Auth::user()->name ? Auth::user()->name : Auth::user()->mobile;
        $res['description'] = '您的好友【'.$name.'】邀请您加入魔客，轻松赚尽360行，让每一次分享创造价值！';
        $res['logo'] = env('QINIU_DOMAIN').'moker/logo.png';
        return response()->json($res);
    }

    /**
     * @SWG\get(path="/moker/1.json",
     *   tags={"moker"},
     *   summary="根据id获取moker信息 不授权",
     *   description="根据id获取moker信息 不授权",
     *   operationId="getMokerById",
     *   produces={"application/json"},
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功（name：魔客姓名；mobile：手机号码；avatar：头像）",
     *      @SWG\Schema(example=
    {
    "moker": {
    "id": 1,
    "mobile": "13968154713",
    "name": "李白",
    "avatar": null,
    "gender": 1,
    "birthday": "2008-11-06",
    "balance": "1000.54",
    "status": 1,
    "device_id": "f421bc7294c44d4c90e691119beaa4fc",
    "device_type": 0,
    "level_id": 2,
    "province_id": 0,
    "city_id": 0,
    "district_id": 0,
    "created_at": "2017-11-22 16:01:16",
    "updated_at": "2017-11-29 17:13:06"
    }
    }

    )
     *   )
     * )
     */
    public function getMokerById($id,MokerService $mokerService)
    {
        $res = $mokerService->getmoker($id);
        $res['ios_url'] = 'https://fir.im/4bl1';
        $res['android_url'] = 'https://fir.im/4bl1';
        return $res;
    }

}
