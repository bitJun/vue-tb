<?php
/**
 * Created by PhpStorm.
 * User: yaobin
 * Date: 2017/12/18
 * Time: 上午10:12
 */

namespace App\Services\Partner;

use App\Repositories\Web\MokerRepository;
use App\Repositories\Web\PartnerRepository;
use App\Repositories\Web\PartnerShopRepository;
use App\Repositories\Web\RegionRepository;

class FacilitatorService
{
    protected $partnerRepository;
    protected $regionRepository;
    protected $partnerShopRepository;
    protected $mokerRepository;

    public function __construct(PartnerRepository $partnerRepository, RegionRepository $regionRepository, PartnerShopRepository $partnerShopRepository, MokerRepository $mokerRepository)
    {
        $this->partnerRepository = $partnerRepository;
        $this->regionRepository = $regionRepository;
        $this->partnerShopRepository = $partnerShopRepository;
        $this->mokerRepository = $mokerRepository;
    }


    /**
     * 根据服务商id获取服务商信息详情
     * @param $id : 服务商id
     * @return array|bool
     */
    public function __getInfo($id)
    {
        //服务商的信息
        $info = $this->partnerRepository->__getInfo($id);
        if (!$info) return false;
        $info['province_name'] = $this->regionRepository->__getRegionInfo([$info['province_id']]);
        $info['city_name'] = $info['city_id'] != 0 ? $this->regionRepository->__getRegionInfo([$info['city_id']]) : '';
        $info['district_name'] = $info['district_id'] != 0 ? $this->regionRepository->__getRegionInfo([$info['district_id']]) : '';
        $result = compact('info');
        return $result;
    }

    /**
     * 根据id获取运营商的详细信息
     * @param $id
     * @return array|bool
     */
    public function __getPartnerDetails($id)
    {
        //服务商的信息
        $info = $this->partnerRepository->__getInfo($id);
        if (!$info) return false;
        $info['endtime'] = strtotime($info['expire_at']);
        unset($info['expire_at'], $info['created_time']);
        $map = [$info['province_id'], $info['city_id'], $info['district_id']];
        $info['region'] = $this->regionRepository->__getRegionInfo($map);
        $areaMap = $info['district_id'] == 0 ? [$info['city_id']] : [$info['district_id']];
        $info['area'] = $this->regionRepository->__getRegionInfo($areaMap);
        //获取服务商信息统计
        $statistics = $this->partnerStatistics($id);
        $result = compact('info');
        return $result;
    }

    /**
     * 服务商信息统计
     * @param $id : 服务商id
     * @param $region : 地址信息
     * @note : 累计收益额  下级服务商 辖区商家数  辖区魔客数
     * @return array|bool
     */
    public function partnerStatistics($id, $region = null)
    {
        // * 后期添加缓存

        //获取累计收益额
        $partnerWhere = ['id' => $id];
        $income = $this->partnerRepository->__getOneByField($partnerWhere, 'income');
        //获取辖区服务商
        $subordinateCount = $this->partnerRepository->__getSubordinateCount($id);
        if (!$region) {
            $region = $this->partnerRepository->__getInfo($id);
        }
        if ($region['province_id'] == 0) {
            //获取辖区商家数
            $shopCount = 0;
            //获取辖区魔客数
            $mokerCount = 0;
        } else {
            $shopWhere['province_pid'] = $mokerWhere['province_id'] = $region['province_id'];
            $region['city_id'] != 0 ? $shopWhere['city_pid'] = $mokerWhere['city_id'] = $region['city_id'] : '';
            $region['district_id'] != 0 ? $shopWhere['district_pid'] = $mokerWhere['district_id'] = $region['district_id'] : '';
            $shopCount = $this->partnerShopRepository->__getShopCount($shopWhere);
            $mokerCount = $this->mokerRepository->__getMokerCount($mokerWhere);
        }
        return compact('income', 'subordinateCount', 'shopCount', 'mokerCount');
    }

    /**
     * 根据服务商id获取服务商的下级列表
     * @param $id
     * @param array $info
     * @return string|bool
     */
    public function __getPartnerList($id, $info)
    {
        //根据服务商id获取服务商的下级列表
        $result = $this->partnerRepository->__getPartnerList($id, $info);
        if ($result['count'] != 0) {
            $regionList = $this->regionRepository->__getAllRegion();
            foreach ($result['info'] as $key => $value) {
                if ($value['district_id'] != 0) {
                    $result['info'][$key]['next_level'] = 0;
                    $result['info'][$key]['city'] = $regionList[$value['district_id']]['label'];
                } else {
                    $result['info'][$key]['next_level'] = 1;
                    $result['info'][$key]['city'] = $regionList[$value['district_id']]['label'];

                }
            }
        }
        return $result;
    }


    /**
     * 添加下级运营
     * @param $info
     * @return bool
     */
    public function store($info)
    {
        //获取城市列表，判断是否符合要求
        $regionList = $this->regionRepository->__getAllRegionIdArr();
        if (!in_array($info['province_id'], $regionList)) {
            return false;
        }
        if (!in_array($info['city_id'], $regionList)) {
            return false;
        }
        if ($info['district_id'] != 0 && (!in_array($info['district_id'], $regionList))) {
            return false;
        }

        //添加运营商及运营商用户信息
        $info['expire_at'] = date('Y-m-d H:i:s', $info['expire_at'] / 1000);

        $result = $this->partnerRepository->__addPartner($info);
        return $result;
    }

    /**
     * 修改下级运营商信息
     * @param $info
     * @return bool
     */
    public function update($id, $info)
    {
        isset($info['expire_at']) ? $info['expire_at'] = date('Y-m-d H:i:s', $info['expire_at']) : '';
        $result = $this->partnerRepository->update($id, $info);
        return $result;
    }

}