<?php
namespace App\Services\Shop;

use App\Model\Partner;
use App\Model\PartnerShop;
use App\Model\Region;
use App\Model\ShopTag;
use App\Repositories\Web\PartnerShopRepository;
use App\Repositories\Web\ShopRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ShopService
{
    public function postShop($params)
    {
        if(isset($params['num_license']['img']))
        {
            $params['num_license'] = $params['num_license']['img'];
        }
        if(isset($params['logo']['img']))
        {
            $params['logo'] = $params['logo']['img'];
        }
        //调用魔店app的接口
        $shop_apply_id = 0;
        $url = getModianUrl().'/api/shop_apply.json';
        $client = new Client();
        $res = $client->post($url, ['form_params'=>$params]);
        if($res->getStatusCode() == 200)
        {
            $res = $res->getBody()->getContents();
            $shopApply = json_decode($res,true);
            $shopApply = $shopApply['shop_apply'];
            $shop_apply_id = $shopApply['id'];
        }
        //添加moker_shop关系表
        if($shop_apply_id)
        {
            $partnerShopData = [
                'shop_id' => 0,
                'shop_apply_id' => $shop_apply_id
            ];
            $partner = Partner::select('id','district_id','city_id','province_id')->where('id',Auth::user()->partner_id)->first();
            if($partner->district_id > 0)
            {
                $partnerShopData['district_pid'] = $partner->id;
            }elseif($partner->city_id > 0)
            {
                $partnerShopData['city_pid'] = $partner->id;
            }elseif($partner->province_id > 0)
            {
                $partnerShopData['province_pid'] = $partner->id;
            }
            PartnerShop::create($partnerShopData);
        }
        return ['status'=>true];
    }


    public function getPartnerShops($params){
        $query = PartnerShop::select('modian.shop.id','modian.shop.name','modian.shop.contact','modian.shop.tel','modian.shop.created_at','modian.shop.province','modian.shop.city','modian.shop.district')
            ->leftJoin('modian.shop', 'shop.id', '=', 'partner_shop.shop_id')
            ->where('partner_shop.shop_id','!=',0);

        $partner = Partner::select('id','district_id','city_id','province_id')->where('id',Auth::user()->partner_id)->first();
        if($partner->district_id > 0)
        {
            $query->where('district_pid', $partner->id);
        }elseif($partner->city_id > 0)
        {
            $query->where('city_pid',$partner->id);
        }elseif($partner->province_id > 0)
        {
            $query->where('province_pid',$partner->id);
        }

        if($params['name'])
        {
            $query->where('name',$params['name']);
        }
        if($params['tel'])
        {
            $query->where('tel',$params['tel']);
        }
        if($params['contact'])
        {
            $query->where('contact',$params['contact']);
        }

        $result['_count'] = $query->count();
        if($result['_count'])
        {
            $result['data'] = $query->orderBy('shop.created_at', 'desc')->skip($params['offset'])->take($params['limit'])->get()->toArray();
            foreach ($result['data'] as &$_data){
                if($_data['province'])
                {
                    $_data['province_str'] = Region::where('id',$_data['province'])->value('name');
                }
                if($_data['city'])
                {
                    $_data['city_str'] = Region::where('id',$_data['city'])->value('name');
                }
                if($_data['district'])
                {
                    $_data['district_str'] = Region::where('id',$_data['district'])->value('name');
                }
            }
        }

        return $result;
    }

    public function getShop($shop_id)
    {
        $PartnerShopRepository=new partnerShopRepository();
        $partner_id = Auth::user()->partner_id;
        return $PartnerShopRepository->__getShop($shop_id,$partner_id);
    }

    public function putShop($shop_id,$params,PartnerShopRepository $partnerShopRepository,ShopRepository $shopRepository)
    {
        $partner_id = Auth::user()->partner_id;
        if(!$partnerShopRepository->__isPartnerShop($shop_id,$partner_id))
        {
            return ['status'=>false,'msg'=>'未找到相关店铺'];
        }
        $data = [];
        if(isset($params['name'])) {
            $data['name'] = $params['name'];
        }
        if(isset($params['contact'])) {
            $data['contact'] = $params['contact'];
        }
        if(isset($params['tel'])) {
            $data['tel'] = $params['tel'];
        }
        if(isset($params['address'])) {
            $data['address'] = $params['address'];
        }
        if(isset($params['logo']['img'])) {
            $data['logo'] = $params['logo']['img'];
        }
        if(isset($params['intro'])) {
            $data['intro'] = $params['intro'];
        }
        if(isset($params['imgs']) && $params['imgs'])
        {
            $imgArr = [];
            if(is_array($params['imgs']))
            {
                foreach ($params['imgs'] as $v)
                {
                    $imgArr[] = $v;
                }
                $data['imgs'] = json_encode($imgArr);
            }else{
                $imgArr = $params['imgs'];
                $data['imgs'] = json_encode($imgArr);
            }
        }
        if(!$data)
        {
            return ['status'=>false,'msg'=>'没有要修改的数据'];
        }
        if($partnerShopRepository->__putShop($shop_id,$params))
        {
            if(isset($params['tag_id'])) {
                $shopRepository->__putShopTag($shop_id,$params['tag_id']);
            }
            if(isset($params['address'])) {
                $shopRepository->__putShopMongo($shop_id,$params['address']);
            }
        }
        return ['status'=>true];
    }
}