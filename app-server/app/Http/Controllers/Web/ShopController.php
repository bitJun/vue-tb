<?php

namespace App\Http\Controllers\Web;

use App\Services\Shop\ShopService;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index(ShopService $shopService)
    {
        $data = $shopService->auditShops();
        //dd($data);
        if($data)
        {
            foreach ($data as &$v)
            {
                $v['num_license'] = getImageUrl($v['num_license']).'-timeline.thumb';
            }
        }
        return view('temp_shops',['data'=>$data]);
    }

    public function shop($id,ShopService $shopService)
    {
        $data = $shopService->getShopApply($id);
        if(isset($data['status']) && !$data['status'])
        {
            return redirect('/shops');
        }
        return view('temp_shop',['data'=>$data]);
    }
}
