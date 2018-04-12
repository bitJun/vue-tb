<?php

namespace App\Services\Timeline;

use App\Jobs\SyncCreatedTimeline;
use App\Jobs\SyncDeletedTimeline;
use App\Jobs\SyncUpdatedTimeline;
use App\Model\Shop;
use App\Model\ShopMember;
use App\Model\ShopMemberTimeline;
use App\Model\ShopTimeline;
use App\Services\Member\ShopMemberService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TimelineService
{
    public function getTimelines($params)
    {
        $shop_id = Auth::user()->shop_id;
        $query = ShopTimeline::select('id','shop_id','shop_name','imgs','content','link','praise','created_at')
            ->where('shop_id',$shop_id);
        if(isset($params['keyword']) && $params['keyword'])
        {
            $query->where('content','like','%'.$params['content'].'%');
        }
        $data['_count'] = $query->count();
        if($data['_count'])
        {
            $data['data'] = $query->take($params['limit'])->skip($params['offset'])->orderBy('id','desc')->get()->toArray();
            foreach ($data['data'] as &$v)
            {
                if($v['imgs'])
                {
                    $imgdata = [];
                    $images = json_decode($v['imgs']);
                    foreach ($images as $k=>$img)
                    {
                        $imgdata[$k]['view'] = getImageUrl($img)."-timeline.view";
                        $imgdata[$k]['thumb'] = getImageUrl($img)."-timeline.thumb";
                        $v['imgs'] = $imgdata;
                    }
                }else{
                    $v['imgs'] = [];
                }
            }
        }else{
            $data['data'] = [];
        }
        return $data;
    }

    public function postTimeline($params)
    {
        $shop_id = Auth::user()->shop_id;
        $shop_name = Shop::where('id',$shop_id)->value('name');
        $data = [
            'shop_id' => $shop_id,
            'shop_name' => $shop_name,
            'imgs' => "",
            'content' => isset($params['content']) ? $params['content'] : "",
            'link' => isset($params['link']) ? $params['link'] : "",
            'praise' => 0
        ];

        if(isset($params['imgs']) && $params['imgs'])
        {
            $imgArr = [];
            if(is_array($params['imgs']))
            {
                foreach ($params['imgs'] as $v)
                {
                    $imgArr[] = $v['img'];
                }
                $data['imgs'] = json_encode($imgArr);
            }
        }

        $data = ShopTimeline::create($data);
        if($data)
        {
            //给所有该店铺的会员推送商圈
            $shopMemberModel = new ShopMember($shop_id);
            $query = $shopMemberModel->where(['shop_id'=>$shop_id]);
            $count = $query->count();
            if($count > 0)
            {
                $offset = 0;
                $limit = 1;
                $job = (new SyncCreatedTimeline($data, $count, $offset, $limit))->onQueue('timeline');
                dispatch($job);
            }
        }
        return $data;
    }

    public function getTimeline($id)
    {
        $shop_id = Auth::user()->shop_id;
        $data = ShopTimeline::where('id',$id)->where('shop_id',$shop_id)->first();
        if($data['imgs'])
        {
            $imgdata = [];
            $images = json_decode($data['imgs']);
            foreach ($images as $k=>$img)
            {
                $imgdata[$k]['url'] = getImageUrl($img);
                $imgdata[$k]['img'] = $img;
            }
            $data['imgs'] = $imgdata;
        }else{
            $data['imgs'] = [];
        }
        return $data;
    }

    public function putTimeline($id,$params)
    {
        $shop_id = Auth::user()->shop_id;
        $imgs = isset($params['imgs']) ? $params['imgs'] : '';
        $content = isset($params['content']) ? $params['content'] : '';
        $link = isset($params['link']) ? $params['link'] : '';

        $data = [];

        $timeline = ShopTimeline::where('id',$id)->where('shop_id',$shop_id)->first();
        if(!$timeline)
        {
            return ['status'=>false,'msg'=>"没找到该商圈"];
        }

        if($imgs)
        {
            $imgArr = [];
            if(is_array($imgs))
            {
                foreach ($imgs as $v)
                {
                    $imgArr[] = $v['img'];
                }
                $data['imgs'] = json_encode($imgArr);
            }
        }
        if($content)
        {
            $data['content'] = $content;
        }
        if($link)
        {
            $data['link'] = $link;
        }
        if($data)
        {
            if(ShopTimeline::where('id',$id)->where('shop_id',$shop_id)->update($data))
            {
                $count = ShopMemberTimeline::where('shop_id',$shop_id)->where('shop_timeline_id',$id)->count();
                if($count > 0)
                {
                    $offset = 0;
                    $limit = 1;
                    $job = (new SyncUpdatedTimeline($id,$data,$count,$offset,$limit))->onQueue('timeline');
                    dispatch($job);
                }
            }
        }
        return ['status'=>true];
    }

    public function deleteTimeline($id)
    {
        $shop_id = Auth::user()->shop_id;
        $timeline = ShopTimeline::where('id',$id)->where('shop_id',$shop_id)->first();
        if(!$timeline)
        {
            return ['status'=>false,'msg'=>"没找到该商圈"];
        }
        if(ShopTimeline::where('id',$id)->where('shop_id',$shop_id)->delete())
        {
            //删除用户商圈信息
            $job = (new SyncDeletedTimeline($id,$shop_id))->onQueue('timeline');
            dispatch($job);
        }
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 队列中调用推送新增商圈信息
     */
    public function syncCreatedTimelineToMember($timeline,$count,$offset=0,$limit=500)
    {
        if(!$timeline)
        {
            return false;
        }

        //获取店铺的所有会员
        $shopMemberModel = new ShopMember($timeline['shop_id']);
        $shopMemberIds = $shopMemberModel->select('member_id')->where(['shop_id'=>$timeline['shop_id']])->orderBy('id','asc')->take($limit)->skip($offset)->get()->toArray();
        if($shopMemberIds)
        {
            foreach ($shopMemberIds as $v)
            {
                $data = [
                    'shop_timeline_id' => $timeline['id'],
                    'shop_id' => $timeline['shop_id'],
                    'shop_name' => $timeline['shop_name'],
                    'member_id' => $v['member_id'],
                    'imgs' => $timeline['imgs'] ? json_encode(json_decode($timeline['imgs'],true)) : "",
                    'content' => $timeline['content'],
                    'link' => $timeline['link'],
                    'is_praise' => 0,
                    'created_at' => $timeline['created_at'],
                    'updated_at' => $timeline['updated_at'],
                ];
                ShopMemberTimeline::create($data);
            }
        }

        if($offset+$limit < $count) {
            //执行队列
            $job = (new SyncCreatedTimeline($timeline,$count, $offset+$limit,  $limit))->onQueue('timeline');
            dispatch($job);
        }
        return true;
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 队列中调用推送修改商圈信息
     */
    public function syncUpdatedTimelineToMember($timeline_id,$data,$count,$offset=0,$limit=500)
    {
        $shopTimeline = ShopTimeline::where('id',$timeline_id)->first();
        if(!$shopTimeline)
        {
            return true;
        }
        //获取店铺的所有会员该条商圈的记录
        $shopMemberTimelines = ShopMemberTimeline::where('shop_id',$shopTimeline['shop_id'])->where('shop_timeline_id',$timeline_id)->orderBy('id','asc')->skip($offset)->take($limit)->get()->toArray();
        if($shopMemberTimelines)
        {
            foreach ($shopMemberTimelines as $v)
            {
                ShopMemberTimeline::where('id',$v['id'])->update($data);
            }
        }

        if($offset+$limit < $count) {
            //执行队列
            $job = (new SyncUpdatedTimeline($timeline_id,$data,$count, $offset+$limit,  $limit))->onQueue('timeline');
            dispatch($job);
        }
        return true;
    }

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * 队列中调用推送删除商圈信息
     */
    public function syncDeletedTimelineToMember($timeline_id,$shop_id)
    {
        $shopMemberTimelines = ShopMemberTimeline::where('shop_id',$shop_id)->where('shop_timeline_id',$timeline_id)->delete();
        return true;
    }
}
