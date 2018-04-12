<?php
/**
 * Created by PhpStorm.
 * User: yaobin
 * Date: 2017/12/18
 * Time: 下午4:18
 */

namespace App\Repositories\Web;


use App\Model\Partner;
use App\Model\PartnerShop;
use App\Model\Shop;
use Illuminate\Support\Facades\Auth;

class PartnerShopRepository
{
    /**
     * 获取服务商辖区的商铺总数
     * @param $map
     * @return int
     */
    public function __getShopCount($map)
    {
        $result = PartnerShop::where($map)
            ->where('status','=',1)
            ->count();
        return $result ?: 0;
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 获取店铺详细信息
     */
    public function __getShop($shop_id,$partner_id)
    {
        $query = PartnerShop::select('modian.shop.*')
            ->leftJoin('modian.shop', 'shop.id', '=', 'partner_shop.shop_id')
            ->where('partner_shop.shop_id','!=',0)
            ->where('modian.shop.id',$shop_id);

        $partner = Partner::select('id','district_id','city_id','province_id')->where('id',$partner_id)->first();
        if($partner->district_id > 0)
        {
            $query->where('district_pid',$partner->id);
        }elseif($partner->city_id > 0)
        {
            $query->where('city_pid',$partner->id);
        }elseif($partner->province_id > 0)
        {
            $query->where('province_pid',$partner->id);
        }
        return $query->first();
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 检查是否是当前代理商关联店铺
     */
    public function __isPartnerShop($shop_id,$partner_id)
    {
        $query = PartnerShop::select('id')
            ->where('shop_id',$shop_id);

        $partner = Partner::select('id','district_id','city_id','province_id')->where('id',$partner_id)->first();
        if($partner->district_id > 0)
        {
            $query->where('district_pid',$partner->id);
        }elseif($partner->city_id > 0)
        {
            $query->where('city_pid',$partner->id);
        }elseif($partner->province_id > 0)
        {
            $query->where('province_pid',$partner->id);
        }
        $data = $query->first();
        if($data)
        {
            return true;
        }
        return false;
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 修改店铺基本信息 (name,contact,tel,address,logo,intro,imgs)
     */
    public function __putShop($shop_id,$params)
    {
        return Shop::where('id',$shop_id)->update($params);
    }
}