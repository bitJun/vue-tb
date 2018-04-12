<?php
/**
 * Created by PhpStorm.
 * User: lufee(ldw1007@sina.cn)
 * Date: 2017/7/8
 * Time: 下午3:10
 */

namespace App\Http\Controllers\Api\Shop;


use App\Http\Controllers\Controller;
use App\Services\Shop\TagService;
use Illuminate\Support\Facades\Response;

class TagController extends Controller
{
    public function getTags(TagService $tagService)
    {
        $tags = $tagService->getTags();
        return Response::json($tags);
    }
}