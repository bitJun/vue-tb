<?php

namespace App\Http\Controllers\Api\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Qiniu\Storage\UploadManager;

class QiniuController extends Controller
{
    /**
     * 获取七牛云uptoken
     */
    public function qiniuToken()
    {
        $ak = env('QINIU_ACCESS_KEY');
        $sk = env('QINIU_SECRET_KEY');
        $bucket = env('QINIU_BUCKET');
        $auth = new \Qiniu\Auth($ak, $sk);
        $policy = array(
            'saveKey' => '$(year)/$(mon)/$(day)/$(etag)$(ext)',
            'callbackUrl' => 'http://183.129.168.195:80/api/qiniu/callback',//url('api/qiniu/callback'),
            'callbackBody' => 'shop_id=$(x:shop_id)&url=$(key)&file_name=$(fname)&file_size=$(fsize)&file_ext=$(ext)&mime_type=$(mimeType)&imageinfo=$(imageInfo)&avinfo=$(avinfo)',
        );

        $token = $auth->uploadToken($bucket, null, 3600, $policy);
        return Response::json(['token' => $token]);
    }

    /**
     * 七牛云回调
     */
    public function qiniuCallback() {
        $resp = Request::all();
        return Response::json($resp);
    }

    public function qiniuUpload() {
        $file = Request::file('file');
        $pathName = $file->getPathName();
        $fileName= $file->getClientOriginalName();
        $ak = env('QINIU_ACCESS_KEY');
        $sk = env('QINIU_SECRET_KEY');
        $bucket = env('QINIU_BUCKET');
        $auth = new \Qiniu\Auth($ak, $sk);
        move_uploaded_file($pathName,$pathName.'temp');
        $pathName = $pathName.'temp';
        $policy = array(
            'saveKey' => '$(year)/$(mon)/$(day)/$(etag)$(ext)',
            'callbackUrl' => url('api/qiniu/callback'),//'http://183.129.168.195:80/api/qiniu/callback',
            'callbackBody' => 'tmp_name='.$pathName.'&shop_id='.Request::input('shop_id').'&url=$(key)&file_name='.$fileName.'&file_size=$(fsize)&file_ext=$(ext)&mime_type=$(mimeType)&imageinfo=$(imageInfo)&avinfo=$(avinfo)',
        );
        $token = $auth->uploadToken($bucket, null, 3600, $policy);

        $uploadMgr = new UploadManager();

        list($ret, $err) = $uploadMgr->putFile($token, null, $pathName);
        return $ret;
    }
}
