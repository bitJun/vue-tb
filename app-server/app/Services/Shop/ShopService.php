<?php
namespace App\Services\Shop;

use App\Facades\Gaode;
use App\Model\Moker;
use App\Model\MokerShop;
use App\Model\Region;
use App\Model\Shop;
use App\Model\ShopApply;
use App\Model\ShopMongo;
use App\Model\ShopTag;
use App\Model\Tag;
use App\Model\User;
use App\Services\Utils\RegionService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ShopService
{
    public function postShop($params)
    {
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
            $mokerShopData = [
                'moker_id' => Auth::user()->id,
                'shop_id' => 0,
                'shop_apply_id' => $shop_apply_id
            ];
            MokerShop::create($mokerShopData);
        }
        return ['status'=>true];
    }


    public function getMokerShops($params){
        $query = MokerShop::where('moker_id',$params['moker_id']);

        if($params['type'] == 'nopass') {
            $query->whereIn('status',[0,2]);
        }else{
            $query->where('status',1);
        }
        $result = $query->orderBy('created_at', 'desc')->paginate($params['limit']);

        return $result;
    }

    public function getMokerShop($id){
        $shop = MokerShop::where('id',$id)->first();
        if($shop){
            $shop = $shop->toArray();
            $shopApplyModel = new ShopApply();
            $shopApply = $shopApplyModel->where('id',$shop['shop_apply_id'])->first();
            if ($shopApply) {
                $shop['shop_name'] = $shopApply['company'];
                $shop['shop_logo'] = $shopApply['logo'];//getImageUrl($shopApply['logo']);
                //营业执照
                $shop['num_license'] = $shopApply['num_license'];//getImageUrl($shopApply['num_license']);
                //店铺图片
                $shop['imgs'] = $shopApply['imgs'] ? json_decode($shopApply['imgs'],true) : '';
                /*
                if($shop['imgs']){
                    foreach($shop['imgs'] as &$imgs) {
                        $imgs = getImageUrl($imgs);
                    }
                }
                */
                $shop['description'] = $shopApply['description'];
                $shop['mobile'] = $shopApply['mobile'];
                $shop['contact'] = $shopApply['contact'];

                //tag
                $shop['tag_id'] = $shopApply['tag_id'];
                $shop['tag_name'] = Tag::where('id',$shopApply['tag_id'])->value('title');

                $shop['province'] = $shopApply['province'];
                $shop['city'] = $shopApply['city'];
                $shop['district'] = $shopApply['district'];
                
                $regionService = new RegionService();
                $region = $regionService->getRegion($shopApply['province']);
                $shop['province_name'] = $region['name'];
                $region = $regionService->getRegion($shopApply['city']);
                $shop['city_name'] = $region['name'];
                $region = $regionService->getRegion($shopApply['district']);
                $shop['district_name'] = $region['name'];

                $shop['address'] = $shopApply['address'];
            }
        }

        return $shop;
    }

    public function getShopApply($id)
    {
        if(!$mokerShop = $this->checkMokerShop($id))
        {
            return ['status'=>false,'message'=>'非法请求'];
        }
        $data = ShopApply::where('id',$mokerShop['shop_apply_id'])->first();
        if($data)
        {
            if($data['tag_id'])
            {
                $data['tag_str'] = Tag::where('id',$data['tag_id'])->value('title');
            }
            if($data['province'])
            {
                $data['province_str'] = Region::where('id',$data['province'])->value('name');
            }
            if($data['city'])
            {
                $data['city_str'] = Region::where('id',$data['city'])->value('name');
            }
            if($data['district'])
            {
                $data['district_str'] = Region::where('id',$data['district'])->value('name');
            }
            if($data['logo'])
            {
                $data['logo_path'] = getImageUrl($data['logo']);
            }
            if($data['num_license'])
            {
                $data['num_license_path'] = getImageUrl($data['num_license']);
            }
            if($data['imgs'])
            {
                $imgArr = [];
                $imgs = json_decode($data['imgs'],true);
                foreach ($imgs as $v)
                {
                    $imgArr[] = getImageUrl($v);
                }
                $data['imgs_path'] = $imgArr;
            }
        }
        return $data;
    }

    public function putShopApply($id,$params)
    {
        if(!$mokerShop = $this->checkMokerShop($id))
        {
            return ['status'=>false,'message'=>'非法请求'];
        }

        $shopApply = ShopApply::where('id',$mokerShop['shop_apply_id'])->first();
        if(!$shopApply)
        {
            return ['status'=>false,'message'=>'未找到相关信息'];
        }

        $data = [
            'company' => $params['company'],
            'logo' => $params['logo'],
            'province' => $params['province'],
            'city' => $params['city'],
            'address' => $params['address'],
            'contact' => $params['contact'],
            'num_license' => $params['num_license'],
            'mobile' => $params['mobile'],
            'tag_id' => $params['tag_id'],
            'description' => $params['description']
        ];

        if(isset($params['district']) && $params['district'])
        {
            $data['district'] = $params['district'];
        }else{
            $data['district'] = 0;
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

        if(ShopApply::where('id',$mokerShop['shop_apply_id'])->update($data))
        {
            //拒绝后重新提交修改状态为未审核
            MokerShop::where('id',$mokerShop['id'])->update(['status'=>0]);
        }
        return ['status'=>true];
    }

    private function checkMokerShop($moker_shop_id)
    {
        $query = MokerShop::where('id',$moker_shop_id);
        if(isset(Auth::user()->id))
        {
            $query->where('moker_id',Auth::user()->id);
        }
        return $query->first();
    }

    public function auditShops()
    {
        $result = MokerShop::where('status',0)->orderBy('created_at', 'desc')->get()->toArray();
        if($result)
        {
            $shopApplyModel = new ShopApply();
            foreach ($result as &$_data){
                $shop = $shopApplyModel->where('id',$_data['shop_apply_id'])->first();
                if($shop)
                {
                    $_data['shop_name'] = $shop['company'];
                    $_data['shop_logo'] = $shop['logo'];
                    $_data['contact'] = $shop['contact'];
                    $_data['mobile'] = $shop['mobile'];
                    $_data['num_license'] = $shop['num_license'];
                }
            }
        }
        return $result;
    }


    public function auditShop($id,$params)
    {
        $shopApply = ShopApply::where('id',$id)->first();
        if(!$shopApply)
        {
            return ['status'=>false,'message'=>'未知参数'];
        }

        //判断手机号码是否已存在
        $user = User::where('mobile',$params['mobile'])->first();
        if(!$user)
        {
            return ['status'=>false,'message'=>'该手机号已经是登录账号了，请重新填写'];
        }

        //增加到shop表
        $lng = 0;
        $lat = 0;
        $provinceName = Region::where('id',$shopApply->province)->value('name');
        $cityName = Region::where('id',$shopApply->city)->value('name');
        $address = $provinceName.$cityName;
        $gaode = Gaode::geoCode($address);
        $gaode = json_decode($gaode,true);
        if($gaode['status'] == 1)
        {
            $geocodes = $gaode['geocodes'];
            if($geocodes)
            {
                $location = $geocodes[0]['location'];
                $locations = explode(',',$location);
                $lng = $locations[0];
                $lat = $locations[1];
            }
        }

        $data = [
            'name' => $shopApply['company'],
            'logo' => $shopApply['logo'],
            'tel' => $shopApply['mobile'],
            'contact' => $shopApply['contact'],
            'intro' => $shopApply['description'],
            'province' => $shopApply['province'],
            'city' => $shopApply['city'],
            'district' => $shopApply['district'],
            'address' => $shopApply['address'],
            'lng' => $lng,
            'lat' => $lat,
            'type' => 1
        ];

        $shop = Shop::create($data);
        if($shop)
        {
            //添加类目
            $tag = [];
            if(isset($shopApply['tag_id']))
            {
                if(!is_array($shopApply['tag_id']))
                {
                    $tag = explode(',',$shopApply['tag_id']);
                }
            }
            if($tag)
            {
                foreach ($tag as $v)
                {
                    ShopTag::create([
                        'shop_id'=>$shop->id,
                        'tag_id'=>$v
                    ]);
                }
            }

            //更新shop_apply状态
            ShopApply::where('id',$id)->update(['type'=>1]);

            //更新moker_shop的shop_id和status
            $moderShopData = [
                'shop_id' => $shop->id,
                'status' => 1
            ];
            MokerShop::where('shop_apply_id',$id)->update($moderShopData);

            //魔客的level_id变成2
            $moker_id = MokerShop::where('shop_apply_id',$id)->value('moker_id');
            $moker_id && Moker::where('id',$moker_id)->update(['level_id'=>2]);

            //添加商户后台初始管理员账号
            $userData = [
                'shop_id' => $shop->id,
                'mobile' => $params['mobile'],
                'status' => 0
            ];
            User::create($userData);

            //添加mongo
            $values = [
                '_id' => $shop->id,
                'title' => $shop->name,
                'tag_id' => [intval($shopApply->tag_id)],
                'loc' => [
                    'longitude' => floatval($lng),
                    'latitude' => floatval($lat),
                ],
            ];
            ShopMongo::create($values);
        }
    }
}