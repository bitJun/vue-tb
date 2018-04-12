<?php

namespace App\Http\Controllers\Api\V1\Utils;

use App\Http\Controllers\Controller;
use App\Model\Attachment;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class QiniuController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/qiniu/token.json",
     *   tags={"qiniu"},
     *   summary="获取七牛token",
     *   description="获取七牛token",
     *   operationId="qiniuToken",
     *   produces={"application/json"},
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(
     *          example={"data":"3qSqtojFWifiG1Z_KL-yCKpA5Vj9XxZA_uOzfdWG:onVAc_G3Ew2NNEsjcsgo01dTDn4=:eyJzYXZlS2V5IjoiJCh5ZWFyKVwvJChtb24pXC8kKGRheSlcLyQoZXRhZykkKGV4dCkiLCJjYWxsYmFja1VybCI6Imh0dHBzOlwvXC9hcGkubW9kaWFuLmNvbVwvYXBpXC9xaW5pdVwvY2FsbGJhY2siLCJjYWxsYmFja0JvZHkiOiJzaG9wX2lkPSQoeDpzaG9wX2lkKSZ1cmw9JChrZXkpJmZpbGVfbmFtZT0kKGZuYW1lKSZmaWxlX3NpemU9JChmc2l6ZSkmZmlsZV9leHQ9JChleHQpJm1pbWVfdHlwZT0kKG1pbWVUeXBlKSZpbWFnZWluZm89JChpbWFnZUluZm8pJmF2aW5mbz0kKGF2aW5mbykiLCJzY29wZSI6Im1vZGlhbiIsImRlYWRsaW5lIjoxNTAwNzA2ODE1LCJ1cEhvc3RzIjpbImh0dHA6XC9cL3VwLnFpbml1LmNvbSIsImh0dHA6XC9cL3VwbG9hZC5xaW5pdS5jb20iLCItSCB1cC5xaW5pdS5jb20gaHR0cDpcL1wvMTgzLjEzMS43LjE4Il19"}
     *      )
     *   )
     * )
     */
    public function qiniuToken()
    {
        $ak = env('QINIU_ACCESS_KEY');
        $sk = env('QINIU_SECRET_KEY');
        $bucket = env('QINIU_BUCKET');
        $auth = new \Qiniu\Auth($ak, $sk);
        $policy = array(
            'saveKey' => '$(year)/$(mon)/$(day)/$(etag)$(ext)',
            'callbackUrl' => url('api/qiniu/callback'),
            'callbackBody' => 'url=$(key)&file_name=$(fname)&file_size=$(fsize)&file_ext=$(ext)&mime_type=$(mimeType)&imageinfo=$(imageInfo)&avinfo=$(avinfo)',
        );

        $token = $auth->uploadToken($bucket, null, 3600, $policy);
        return $this->response->array(['data'=>$token]);
    }

    /**
     * 七牛云回调
     */
    public function qiniuCallback() {
        $resp = Request::all();
        return Response::json($resp);
    }
}
