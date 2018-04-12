<?php

namespace App\Http\Controllers\Api\Timeline;

use App\Services\Timeline\TimelineService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class TimelineController extends Controller
{
    public function __construct(TimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function getTimelines(Request $request)
    {
        $params['limit'] = isset($request->limit) ? $request->limit : 10;
        $params['offset'] = isset($request->offset) ? $request->offset : 0;
        $params['keyword'] = isset($request->keyword) ? $request->keyword : '';
        $response = $this->timelineService->getTimelines($params);
        return Response::json($response);
    }

    public function postTimeline(Request $request)
    {
        if(!$request['imgs'] && !$request['content'] && !$request['link'])
        {
            return Response::json(['message'=>'图片、内容和链接至少填写一个'],422);
        }

        $response = $this->timelineService->postTimeline($request->all());
        return Response::json($response);
    }

    public function getTimeline($id)
    {
        $response = $this->timelineService->getTimeline($id);
        return Response::json($response);
    }

    public function putTimeline($id,Request $request)
    {
        if(!$request['imgs'] && !$request['content'] && !$request['link'])
        {
            return Response::json(['message'=>'图片、内容和链接至少填写一个'],422);
        }

        $response = $this->timelineService->putTimeline($id,$request->all());
        return Response::json($response);
    }

    public function deleteTimeline($id)
    {
        $response = $this->timelineService->deleteTimeline($id);
        return Response::json($response);
    }
}
