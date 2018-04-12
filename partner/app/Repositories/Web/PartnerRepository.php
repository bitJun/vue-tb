<?php

// +----------------------------------------------------------------------
// | 服务商的资源库，处理资源库逻辑
// +----------------------------------------------------------------------
// | author     武安y<yaobin24@126.com>
// +----------------------------------------------------------------------
// | note
// +----------------------------------------------------------------------
// | date       2017/12/18 10:29
// +----------------------------------------------------------------------

namespace App\Repositories\Web;


use App\Model\Partner;
use App\Model\PartnerUser;
use function foo\func;
use Illuminate\Support\Facades\DB;

class PartnerRepository
{

    /**
     *  获取服务商的基本信息
     * @param $id
     * @return bool
     */
    public function __getInfo($id)
    {
//        DB::enableQueryLog();
        $result = Partner::leftjoin('partner_user as pu', 'pu.partner_id', '=', 'partner.id')
            ->select('partner.id', 'name', 'partner.mobile', 'pu.mobile as account',
                'company_name', 'province_id', 'city_id', 'district_id', 'address', 'manager',
                DB::raw('DATE_FORMAT(expire_at,\'%Y-%m-%d\') as expire_at'),
                DB::raw('DATE_FORMAT(partner.created_at,\'%Y-%m-%d\') as created_time'))
            ->where(['partner.id' => $id, 'disabled' => 0])
            ->first()
            ->toArray();
//        $results=  response()->json(DB::getQueryLog());
//        print_r($results);die;
        return $result ? $result : false;
    }


    /**
     *  获取服务商的基本信息
     * @param $id
     * @return bool
     */
    public function __getSubordinateCount($id)
    {
        $result = Partner::where(['partner.parent_id' => $id, 'disabled' => 0])
            ->count();
        return $result ? $result : 0;
    }

    /**
     * 获取下级服务商信息
     * @param $id
     * @param $map
     * @return array
     */
    public function __getPartnerList($id, $map)
    {
        $count = $info = Partner::where(
            function ($query) use ($id,$map) {
                $query->where(['parent_id' => $id, 'disabled' => 0]);
                $map['company_name'] && $query->where('company_name', 'like', '%' . $map['company_name'] . '%');
                $map['name'] && $query->where('name', 'like', '%' . $map['name'] . '%');
                $map['mobile'] && $query->where('mobile', 'like', '%' . $map['mobile'] . '%');
            }
            )
            ->count();
        $info = Partner::where(function ($query) use ($id,$map) {
                $query->where(['parent_id' => $id, 'disabled' => 0]);
                $map['company_name'] && $query->where('company_name', 'like', '%' . $map['company_name'] . '%');
                $map['name'] && $query->where('name', 'like', '%' . $map['name'] . '%');
                $map['mobile'] && $query->where('mobile', 'like', '%' . $map['mobile'] . '%');
            })
            ->select('id', 'name', 'mobile', 'company_name', 'city_id','district_id', 'manager', 'created_at', 'expire_at')
            ->take($map['limit'])
            ->offset($map['offset'])
            ->get()
            ->toArray();
        return compact('count', 'info');
    }

    /**
     * 根据map，获取某个字段信息
     * @param $map
     * @param $fields
     * @return bool
     */
    public function __getOneByField($map, $fields)
    {
        $result = Partner::where($map)->select($fields)->first();
        return !empty($result) ? $result->$fields : false;
    }


    /**
     * 添加服务商信息
     * @param $map
     * @return bool
     */
    public function __addPartner($map)
    {
        DB::beginTransaction(); //开启事务

        //添加服务商
        $partner_result = Partner::create($map);
        if (!$partner_result) {
            DB::rollBack();
            return false;
        }
        $user = new PartnerUser();
        $user->partner_id = $partner_result->id;
        $user->mobile = $map['mobile'];
        $user->password = bcrypt(123456);
        $user->enabled = 2;
        $user_result = $user->save();
        if ($user_result) {
            DB::commit();
            return true;
        } else {
            DB::rollBack();
            return false;
        }
    }


    /**
     * @param $id
     * @param $map
     * @return mixed
     */
    public function update($id, $map)
    {
        $partner = Partner::find($id);
        $result = $partner->update($map);
        return $result;
    }


    /**
     * 查询服务商
     * @param $map
     * @return mixed
     */
    public function __queryPartner($map)
    {
        $result = Partner::where(function ($query) use ($map) {
                $map['company_name'] && $query->where('company_name', 'like', '%' . $map['company_name'] . '%');
                $map['name'] && $query->where('name', 'like', '%' . $map['name'] . '%');
                $map['mobile'] && $query->where('mobile', 'like', '%' . $map['mobile'] . '%');
            })
            ->get()
            ->toArray();
        return $result;
    }
}