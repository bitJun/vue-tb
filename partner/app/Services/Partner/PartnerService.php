<?php
namespace App\Services\Partner;

use App\Model\Partner;
use App\Model\PartnerUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PartnerService
{
    public function getPartners($params)
    {
        $query = Partner::select('id','parent_id','name','mobile','created_at');
        if(isset($params['mobile']) && $params['mobile']){
            $query->where('mobile', $params['mobile']);
        }
        if(isset($params['name']) && $params['name']) {
            $query->where('name', 'like', '%'.$params['name'].'%');
        }
        $res['_count'] = $query->count();
        $res['data'] = [];
        if($res['_count']) {
            $res['data'] = $query->take($params['limit'])->skip($params['offset'])->orderBy('id','desc')->get();
        }
        return $res;
    }

    public function putPassword($password,$oldPassword)
    {
        $password = bcrypt($password);
        if(!Hash::check($oldPassword,Auth::user()->password))
        {
            return false;
        }
        $id = Auth::user()->id;
        if(PartnerUser::where(array('partner_id'=>$id))->update(array('password'=>$password)))
        {
            return true;
        }
        return false;
    }
}
