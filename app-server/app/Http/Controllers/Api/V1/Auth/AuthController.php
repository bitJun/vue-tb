<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Jobs\PushMsg;
use App\Model\Moker;
use App\Services\Moker\MokerService;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use Helpers;

    /**
     * @SWG\Post(path="/auth/login.json",
     *   tags={"auth"},
     *   summary="登录接口",
     *   description="登录接口",
     *   operationId="login",
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
     *     name="device_id",
     *     in="query",
     *     description="设备id",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_type",
     *     in="query",
     *     description="设备类型 0:ios,1:android",
     *     required=false,
     *     type="integer"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="登录成功",
     *      @SWG\Property(example={"data": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vYXBpLm1va2VyLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNTExMjU4NzcxLCJuYmYiOjE1MTEyNTg3NzEsImp0aSI6InhiY3hZRTgxYmY3U3loVksiLCJzdWIiOjEsInBydiI6Ijg0Mzg4YTkzOWE3OGE1ZDlmYzVlNTE3NmM2MjM5Y2U0NGFkZTZmZTcifQ.qcb5JK9DH0zsPqZ8zzhP5hC1fSWSyWwG6RvYQCLZ4f8"}),
     *   )
     * )
     */
    public function login(Request $request, MokerService $mokerService) {
        $input = [
            'mobile' => $request->get('mobile'),
            'password' => $request->get('password'),
            'device_id' => $request->get('device_id'),
            'device_type' => $request->get('device_type')
        ];
        $deviceId = $request->get('device_id');
        $deviceType = $request->get('device_type');

        $rules = [
            'mobile' => 'required|digits_between:10,20',
            'password' => 'required',
        ];
        $message = [
            'mobile.required' => '手机号必须填写',
            'mobile.digits_between' => '手机号格式不正确',
            'password.required' => '密码必须填写'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        if(!Moker::where('mobile',$input['mobile'])->first()) {
            $this->response->errorBadRequest('您还不是注册用户，请先注册!');
        }

        try {
            $credentials = $request->only('mobile', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                $this->response->errorBadRequest('用户名或者密码不正确!');
            }
            //设备id 如有变动 则更新
            if ($deviceId && $deviceId != Auth::user()->device_id) {
                $this->offlineDevice(Auth::user()->device_id);
                $mokerService->putMoker(Auth::user()->id, ['device_id' => $deviceId, 'device_type' => $deviceType]);
            }

            return $this->response->array(['data' => $token]);
        } catch (JWTException $e) {
            $this->response->errorInternal('暂时无法生成token');
        }
    }

    /**
     * @SWG\Put(path="/auth/bind_device_id.json",
     *   tags={"auth"},
     *   summary="绑定设备id",
     *   description="绑定设备id",
     *   operationId="bind_device_id",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly93d3cubW9kaWFuLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNDk4OTAyMzQ5LCJleHAiOjE2NTY1ODIzNDksIm5iZiI6MTQ5ODkwMjM0OSwianRpIjoiM3pCSURldmhvWkxFWXBJUSJ9.Jct6XeOsQtjDHvyn1TUkxhea2Na-q_M-NGJHWywEQG0"
     *   ),
     *   @SWG\Parameter(
     *     name="device_id",
     *     in="query",
     *     description="设备id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_type",
     *     in="query",
     *     description="设备类型 0:ios, 1:android",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={
     *              "device_id": "777",
     *              "device_type": 0
     *          }
     *      )
     *   )
     * )
     */
    public function bindDeviceId(Request $request, MokerService $mokerService) {
        $data = $request->all();
        $rules = [
            'device_id' => 'required',
            'device_type' => 'required|in:0,1',
        ];
        $message = [
            'device_id.required' => '设备id不能为空',
            'device_type.required' => '设备类型不能为空',
            'device_type.in' => '设备类型值不正确'
        ];
        $validator = Validator::make($data, $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        $currentMoker = $mokerService->getCurrentMoker();
        if($data['device_id'] && $data['device_id'] != $currentMoker['device_id']) {
            $this->offlineDevice($currentMoker['device_id']);
        }
        $mokerService->bindDeviceId(Auth::user()->id, $data['device_id'], $data['device_type']);
        return $this->response->array($data);
    }

    /**
     * 同一账号只能同时登录一个手机
     * @param $orgDeviceId
     */
    private function offlineDevice($orgDeviceId) {
        if($orgDeviceId) {
            $params['title'] = '下线之前登录的手机';
            $params['body'] = '{"type":1,"device_id":"' . $orgDeviceId . '"}';
            $params['pushType'] = 'MESSAGE';
            $params['targetValue'] = $orgDeviceId;
            //$params['iOSApnsEnv'] = 'DEV';
            $params['iOSSilentNotification'] = true;
            $params['storeOffline'] = 'true';
            $job = new PushMsg($params);
            dispatch($job);
        }
    }

    /**
     * @SWG\Post(path="/auth/logout.json",
     *   tags={"auth"},
     *   summary="退出接口",
     *   description="退出接口",
     *   operationId="logout",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="退出成功",
     *     @SWG\Property(
     *          property="message",
     *          default="退出成功",
     *          type="string"
     *     )
     *   )
     * )
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response->array(['message' => '退出成功']);
    }

    private function checkCaptcha($captcha, $mobile)
    {
        $cacheCaptcha = Redis::get('moker_captcha:'.$mobile);
        if($cacheCaptcha && $cacheCaptcha == $captcha) {
            return true;
        }
        return false;
    }


    /**
     * @SWG\POST(path="/verify.json",
     *   tags={"auth"},
     *   summary="修改密码-验证短信验证码",
     *   description="修改密码-验证短信验证码",
     *   operationId="verifysms",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly93d3cubW9kaWFuLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNDk4OTAyMzQ5LCJleHAiOjE2NTY1ODIzNDksIm5iZiI6MTQ5ODkwMjM0OSwianRpIjoiM3pCSURldmhvWkxFWXBJUSJ9.Jct6XeOsQtjDHvyn1TUkxhea2Na-q_M-NGJHWywEQG0"
     *   ),
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="captcha",
     *     in="query",
     *     description="验证码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          true
     *     }),
     *   )
     * )
     */
    public function verifysms(Request $request) {
        $rules = [
            'mobile' => 'required|digits_between:10,20',
            'captcha' => 'required'
        ];
        $message = [
            'mobile.required' => '手机号必须填写',
            'mobile.digits_between' => '手机号格式不正确',
            'captcha.required' => '手机验证码必须填写'
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        $mobile = $request->mobile;

        //判断是否是当前用户
        if(Auth::user()->mobile != $mobile)
        {
            $this->response->errorBadRequest('非法请求，不是当前用户');
        }

        $cacheCode = Redis::get('moker_app_code:' . $mobile);
        if ($cacheCode && $cacheCode == $request->captcha) {
            //redis增加一个标识，为后面更新接口调用时验证
            Redis::set('moker_find_pass:' . $mobile, 1);
            //Redis::expire('find_pass:'.$mobile,120);
            return $this->response->array(['status' => true]);
        } else {
            $this->response->errorBadRequest('验证码错误');
        }
    }

    /**
     * @SWG\POST(path="/set_new_pass.json",
     *   tags={"auth"},
     *   summary="修改密码-修改密码",
     *   description="修改密码-修改密码",
     *   operationId="setNewPass",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly93d3cubW9kaWFuLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNDk4OTAyMzQ5LCJleHAiOjE2NTY1ODIzNDksIm5iZiI6MTQ5ODkwMjM0OSwianRpIjoiM3pCSURldmhvWkxFWXBJUSJ9.Jct6XeOsQtjDHvyn1TUkxhea2Na-q_M-NGJHWywEQG0"
     *   ),
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="pass",
     *     in="query",
     *     description="新密码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          true
     *     }),
     *   )
     * )
     */
    public function setNewPass(Request $request, MokerService $mokerService) {
        $rules = [
            'mobile' => 'required|digits_between:10,20',
            'pass' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
        ];
        $message = [
            'mobile.required' => '手机号必须填写',
            'mobile.digits_between' => '手机号格式不正确',
            'pass.required' => '新密码必须填写',
            'pass.regex' => '密码至少6位，且必须包含大写字母、小写字母和数字',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        $mobile = $request->mobile;

        //判断是否是当前用户
        if(Auth::user()->mobile != $mobile)
        {
            $this->response->errorBadRequest('非法请求，不是当前用户');
        }

        $cacheFlag = Redis::get('moker_find_pass:' . $mobile);
        if (!$cacheFlag) {
            $this->response->errorBadRequest('请先验证手机号码');
        }

        $moker = $mokerService->getByMobile($request->mobile);
        if (!$moker) {
            $this->response->errorBadRequest('该手机号还未绑定，请先绑定手机号');
        }
        $mokerService->putPassword($request->mobile, $request->pass);
        return $this->response->array(['status' => true]);
    }

    /**
     * @SWG\POST(path="/set_pass.json",
     *   tags={"auth"},
     *   summary="找回密码",
     *   description="找回密码",
     *   operationId="setPass",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="mobile",
     *     in="query",
     *     description="手机号码",
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
     *     name="captcha",
     *     in="query",
     *     description="图片验证码",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="code",
     *     in="query",
     *     description="短信验证码",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *          true
     *     }),
     *   )
     * )
     */
    public function setPass(Request $request, MokerService $mokerService)
    {
        $rules = [
            'mobile' => 'required|digits_between:10,20',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/',
            'captcha' => 'captcha',
            'code' => 'required',
        ];
        $message = [
            'mobile.required' => '请填写手机号',
            'mobile.digits_between' => '手机号格式不正确',
            'password.required' => '请填写密码',
            'password.regex' => '密码至少6位，且必须包含大写字母、小写字母和数字',
            'captcha.captcha' => '图片验证码不正确',
            'code.required' => '请填写短信验证码',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $this->response->errorBadRequest($validator->errors()->first());
        }

        $mobile = $request->mobile;

        $cacheCode = Redis::get('moker_app_code:' . $mobile);
        if ($cacheCode && $cacheCode == $request->code) {
            $moker = $mokerService->getByMobile($request->mobile);
            if (!$moker) {
                $this->response->errorBadRequest('该手机号还未绑定，请先绑定手机号');
            }
            $mokerService->putPassword($request->mobile, $request->password);
            return $this->response->array(['status' => true]);
        } else {
            $this->response->errorBadRequest('验证码错误');
        }
    }
}
