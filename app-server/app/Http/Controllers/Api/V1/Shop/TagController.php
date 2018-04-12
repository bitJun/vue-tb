<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/8
 * Time: 下午3:10
 */

namespace App\Http\Controllers\Api\V1\Shop;


use App\Http\Controllers\Controller;
use App\Services\Shop\TagService;
use Dingo\Api\Routing\Helpers;

class TagController extends Controller
{
    use Helpers;

    /**
     * @SWG\Get(path="/tags.json",
     *   tags={"shop"},
     *   summary="获取行业属性列表",
     *   description="获取行业属性列表",
     *   operationId="getTags",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="授权token",
     *     required=true,
     *     type="string",
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly93d3cubW9kaWFuLmNvbS9hcGkvYXV0aC9sb2dpbi5qc29uIiwiaWF0IjoxNDk4OTAyMzQ5LCJleHAiOjE2NTY1ODIzNDksIm5iZiI6MTQ5ODkwMjM0OSwianRpIjoiM3pCSURldmhvWkxFWXBJUSJ9.Jct6XeOsQtjDHvyn1TUkxhea2Na-q_M-NGJHWywEQG0"
     *   ),
     *   @SWG\Response (
     *      response=200,
     *      description="请求成功",
     *      @SWG\Property(example={
     *      "data" = {
     *           {
     *           "id": 1,
     *           "title": "整形美容（美发美甲瘦身）",
     *           "created_at": "2017-07-08 15:22:04",
     *           "updated_at": "2017-07-08 15:22:04",
     *           "deleted_at": null
     *           },
     *           {
     *           "id": 2,
     *           "title": "餐饮",
     *           "created_at": "2017-07-08 15:22:04",
     *           "updated_at": "2017-07-08 15:22:04",
     *           "deleted_at": null
     *           }
     *     }
     *      }),
     *   )
     * )
     */
    public function getTags(TagService $tagService)
    {
        $tags = $tagService->getTags();
        return $this->response->array(["data"=>$tags]);
    }
}