<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use App\Services\Shop\ShopService;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    public function getQrcodes()
    {
        $user = Auth::user();
        $shopService = new ShopService();
        $shop = $shopService->getShop($user['shop_id']);
        $logo = '';
        if($shop) {
            $logo = getImageUrl($shop['logo']).'-shop.icon?roundPic/radius/10';
        }

        $downloadUrl = 'http://a.app.qq.com/o/simple.jsp?pkgname=com.taotui8.magicstore';
        $downloadQr = QrCode::format('png');
        if($logo) {
            //$downloadQr = $downloadQr->errorCorrection('H')->merge($logo, .2, true);
        }
        $downloadContent = $downloadQr->size(240)->margin(1)->generate($downloadUrl);
        $downloadQrcode = 'data:image/png;base64, '. base64_encode($downloadContent);

        //$cashUrl = getApiDomain().'cashier/'.Auth::user()->shop_id;
        $cashUrl = getPayDomain().'pay/'.base64_encode("s=".Auth::user()->shop_id);
        $cashQr = QrCode::format('png');
        if($logo) {
            $cashQr = $cashQr->errorCorrection('H')->merge($logo, .2, true);
        }
        $cashContent = $cashQr->size(240)->margin(1)->generate($cashUrl);
        $cashQrcode = 'data:image/png;base64, '. base64_encode($cashContent);

        $res['download_qrcode'] = $downloadQrcode;
        $res['cash_qrcode'] = $cashQrcode;
        return response()->json($res);
    }
}
