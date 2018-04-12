<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @SWG\Swagger(
     *   basePath="/api",
     *   @SWG\Info(
     *     title="魔客V1接口",
     *     version="1.0.0",
     *     description="错误码说明,格式:{'message':'xxxxxx', 'status_code':'400'} 400:请求错误,401:未授权,403:禁止访问,404:找不到,405:方法不被允许,500:程序错误"
     *   ),
     *   @SWG\SecurityScheme(
     *         securityDefinition="Bearer",
     *         type="apiKey",
     *         name="Authorization",
     *         in="header"
     *   ),
     * )
     */
}
