<?php

namespace App\Http\Controllers\Web;

use App\Facades\Gaode;
use App\Services\Order\OrderSettledService;
use App\Services\Timeline\TimelineService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use TaoTui\Cashier\Facades\Llpay;

class TestController extends Controller
{
    public function testOrderSettled()
    {
        $servcie = new OrderSettledService(1,'E201710250203426657831');
        $servcie->handle();
        exit('testOrderSettled');
    }

    public function getGaodeInfo(Request $request)
    {
        $address = isset($request->address) ? $request->address : "浙江省杭州市西湖区翠柏路7号杭州电子商务产业园";
        $data = Gaode::geoCode($address);
        dd($data);
    }

    public function systemCreate(TimelineService $timelineService)
    {
        $timelineService->syncCreatedTimelineToMember(17);
    }

    public function testMessage()
    {
        $data =array(
            'mobile' => '15558105636',
        );
        $result = Llpay::smssend($data);
        dd($result);
    }

    public function uploadunitphoto()
    {
        $data['front_card'] = '';//new \CURLFile('');
        $data['back_card'] = '';//new \CURLFile('');
        $res = Llpay::uploadunitphoto($data);
        return $res;
    }
}
