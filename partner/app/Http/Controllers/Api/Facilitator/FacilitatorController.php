<?php

namespace App\Http\Controllers\Api\Facilitator;

use App\Services\Partner\FacilitatorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class FacilitatorController extends Controller
{

    protected $facilitatorService;

    /**
     * FacilitatorController constructor.
     * @param FacilitatorService $facilitatorService
     */
    public function __construct(FacilitatorService $facilitatorService)
    {
        $this->facilitatorService = $facilitatorService;
    }

    /**
     * 服务商详情
     * id :服务商id
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $user = Auth::user();
        $result = $this->facilitatorService->__getInfo($user->id);
        return $result ? response()->json($result) : response()->json(['error' => '暂无该服务商信息'], 205);
    }

    /**
     * 根据id获取运营商的详细信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (!$id) {
            return response()->json('请求参数有误，请核对后再提交', 422);
        }
        //根据id查询详情
        $result = $this->facilitatorService->__getPartnerDetails($id);
        return $result ? response()->json($result) : response()->json(['error' => '暂无该服务商信息'], 205);

    }

    /**
     * 获取服务商下级列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function partnerList(Request $request)
    {
        //检验参数
        $input = [
            'id' => $request->get('id') ?: Auth::user()->id,
            'company_name' => $request->get('company_name'),
            'name' => $request->get('name'),
            'mobile' => $request->get('mobile'),
            'limit' => $request->get('limit'),
            'offset' => $request->get('offset'),
        ];
        $rules = [
            'id' => 'required',
            'limit' => 'required|integer',
            'offset' => 'required|integer',
        ];
        $message = [
            'id.required' => '请求参数有误，请核对后再提交',
            'limit.required' => 'limit必填',
            'limit.integer' => '请求参数有误，请核对后再提交',
            'offset.required' => 'offset必填',
            'offset.integer' => '请求参数有误，请核对后再提交',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        //根据id查询相应的数据
        $result = $this->facilitatorService->__getPartnerList($input['id'], $input);
        return $result ? response()->json($result) : response()->json('暂无更多下级运营商信息', 205);
    }

    /**
     * 添加下级服务商
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //参数验证
        $input = [
            'parent_id' => $request->get('parent_id') ?: Auth::user()->id,
            'company_name' => $request->get('company'),
            'name' => $request->get('account'),
            'mobile' => $request->get('mobile'),
            'province_id' => $request->get('province_id'),
            'city_id' => $request->get('city_id'),
            'district_id' => $request->get('district_id') ?: 0,
            'address' => $request->get('address') ?: '',
            'expire_at' => $request->get('endtime'),
            'manager' => $request->get('manager'),
        ];
        $rules = [
            'parent_id' => 'required|integer',
            'company_name' => 'required',
            'name' => 'required',
            'mobile' => 'required|digits_between:10,20',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'district_id' => 'integer',
            'expire_at' => 'required',
            'manager' => 'required',
        ];
        $message = [
            'parent_id.required' => '请求参数有误，请核对后再提交',
            'parent_id.integer' => '请求参数有误，请核对后再提交',
            'company_name.required' => '公司名称必填',
            'name.required' => '联系人必填',
            'mobile.required' => '手机号码必填',
            'mobile.digits_between' => '手机号码格式不正确',
            'province_id.required' => '请选择服务的省名',
            'province_id.integer' => '请求参数有误，请核对后再提交',
            'city_id.required' => '请选择服务的城市',
            'city_id.integer' => '请求参数有误，请核对后再提交',
            'district_id.integer' => '请求参数有误，请核对后再提交',
            'expire_at.required' => '过期时间必填',
            'manager.required' => '业务经理必填',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }
        //执行添加服务商逻辑
        $result = $this->facilitatorService->store($input);
        return $result ? response()->json('添加成功') : response()->json('添加失败', 417);

    }

    /**
     *  编辑运营商信息
     *
     * @param  int $id : 待编辑的运营商信息
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //参数验证
        $input = [
            'id' => $id,
            'company_name' => $request->get('company_name'),
            'name' => $request->get('name'),
            'mobile' => $request->get('mobile'),
            'expire_at' => $request->get('endtime'),
            'manager' => $request->get('manager'),
        ];
        $request->get('address') ? $input['address'] = $request->get('address') : "";

        $rules = [
            'id' => 'required',
            'company_name' => 'required',
            'name' => 'required',
            'mobile' => 'required|digits_between:10,20',
            'expire_at' => 'required',
            'manager' => 'required',
        ];
        $message = [
            'id.required' => '请求参数有误，请核对后再提交',
            'company_name.required' => '公司名称必填',
            'name.required' => '联系人必填',
            'mobile.required' => '手机号码必填',
            'mobile.digits_between' => '手机号码格式不正确',
            'expire_at.required' => '过期时间必填',
            'manager.required' => '业务经理必填',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        //执行编辑逻辑
        $result = $this->facilitatorService->update($id, $input);
        return $result ? response()->json('编辑成功') : response()->json('编辑失败', 417);


    }

    /**
     * 禁用运营商
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return response()->json(['error' => '请求参数有误，请核对后再提交'], 422);
        }
        $info['disabled'] = 1;
        //执行禁用逻辑
        $result = $this->facilitatorService->update($id, $info);
        return $result ? response()->json('操作成功') : response()->json('操作失败', 417);
    }
}
