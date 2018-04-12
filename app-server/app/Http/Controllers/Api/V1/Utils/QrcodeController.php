<?php

namespace App\Http\Controllers\Api\V1\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrcodeController extends Controller
{
    /**
     * @SWG\Get(path="/invitation/qrcode.json",
     *   tags={"qrcode"},
     *   summary="获取魔客邀请二维码",
     *   description="获取魔客邀请二维码",
     *   operationId="getInvitationQrcode",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token token格式 Bearer {token}",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="disabled_cache",
     *     in="query",
     *     description="正常调用可以不用传这个参数，是否走缓存 0:走 1:不走",
     *     type="integer"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"invitation_qrcode":"iVBORw0KGgoAAAANSUhEUgAAAPAAAADwCAIAAACxN37FAAAEv0lEQVR4nO3dS27cOhQAUdt4+1+ykUFmDzJAt8RPV84Zu6V2XNHgghQ/v7+/P6Dia/cXgCcJmhRBkyJoUgRNiqBJETQpgiZF0KQImhRBkyJoUgRNiqBJETQpgiZF0KT899rHvr6O+58wvvXm8ssv27mz95/u8td867/m/xz3m8AdgiZF0KQImhRBkyJoUl4c2106cPJ1cyA1/vHx333ZePHm737gX3Poag9eC7YTNCmCJkXQpAiaFEGT8uTY7tLe4dH44rIZE7rxuduMn5wxdzt/FOgJTYqgSRE0KYImRdCkCJqU6WO7vW4O4/ZOqWbM8vI8oUkRNCmCJkXQpAiaFEGTEh/bLVsZd9OylXH5WZ4nNCmCJkXQpAiaFEGTImhSpo/t9s6JZoyuZszybq4KfOvx4rM8oUkRNCmCJkXQpAiaFEGT8uTY7sADSWfY+8a6ZWvo3vSv+ZZfGn4iaFIETYqgSRE0KYIm5fP89VOnmfG+vN75sLt4QpMiaFIETYqgSRE0KYIm5cXVdsuWYi3b/jl+9/GfnLFz9sBlfUctFfSEJkXQpAiaFEGTImhSBE3Ki6vtbq44G7/mjMNYLy1bhtabeC47MHfoane+CpxG0KQImhRBkyJoUgRNypNju717QvceGrt36jdjEdy4ozbeekKTImhSBE2KoEkRNCmCJmX6SbKX3mUcdmnZfHD80NhljlpYd32LB68F2wmaFEGTImhSBE2KoEmZPrbbe8jp3pfTzbj7jCWNpZ3IntCkCJoUQZMiaFIETYqgSXnyJNkZ+2EP/Pi7vB5umaPOqfCEJkXQpAiaFEGTImhSBE3K9HfbvcvHL8245ri9s7zz98Ne33f2DWAlQZMiaFIETYqgSRE0KU+utru0bHj0LqdkLLv7za80/vFx3m0HvyNoUgRNiqBJETQpgiZlz9juwHVkB57QemnBPtNHWG0HDxA0KYImRdCkCJoUQZPy4pEUy1acHTiQWnbUw6Xxf/m96/LGebcd/EjQpAiaFEGTImhSBE3KkyfJLjvkdO+mzps32nsM7t639V2ySRZ+JGhSBE2KoEkRNCmCJuWgIynGLVttt/d02mVndCzj3XbwO4ImRdCkCJoUQZMiaFKmj+1mbKe9ae8KvnFvvVxu12TWE5oUQZMiaFIETYqgSRE0KdOPpNjrwPe7vcvUb/yaMxjbwceHoIkRNCmCJkXQpAialOlHUiyzbBx2c5Z34DRt2R5bJ8nC7wiaFEGTImhSBE2KoEmZfiTFDHs33i47p2LvruEDrznCE5oUQZMiaFIETYqgSRE0KU+O7S7t3VK6bBx286CJZedU5N8V6AlNiqBJETQpgiZF0KQImpTpY7sD3RyHzfjJZXtsD9xK/OwWXU9oUgRNiqBJETQpgiZF0KT8i2O7ccv2w45fc8Yaur0bb622gx8JmhRBkyJoUgRNiqBJmT62651Uu/eNdTM2yc6YOe5y+veDXxE0KYImRdCkCJoUQZPy5Nju/JnOX3s3io7/5Iz9sAu2qc675tB9Z98AVhI0KYImRdCkCJoUQZPy2VsNx7/ME5oUQZMiaFIETYqgSRE0KYImRdCkCJoUQZMiaFIETYqgSRE0KYImRdCkCJoUQZPyB9qkSn3jtcr6AAAAAElFTkSuQmCC"}
     *      )
     *   )
     * )
     */
    public function getInvitationQrcode()
    {
        $disabledCache = Request::get('disabled_cache') ? Request::get('disabled_cache') : 0;
        $inviterId = Auth::user()->id;
        $cacheKey = 'moker.invitation_qrcode'.$inviterId;
        if((!$invitationQrcode = Cache::get($cacheKey)) || $disabledCache) {
            $url = env('APP_URL') . '/invitation/' . $inviterId;
            $invitationQr = QrCode::format('png');
            $invitationContent = $invitationQr->size(240)->margin(1)->generate($url);
            $invitationQrcode = base64_encode($invitationContent);
            Cache::set($cacheKey, $invitationQrcode);
        }
        $res['invitation_qrcode'] = $invitationQrcode;
        return response()->json($res);
    }
}
