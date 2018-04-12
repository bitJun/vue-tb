<?php
namespace App\Services\Logs;


use App\Model\Logs;
use Illuminate\Support\Facades\Request;

class LogsService
{

    public function postLogs($params)
    {

        $data = [
            'shop_id' => $params['shop_id'],
            'source' => $params['source'],
            'content' => $params['content'],
            'source_ip'=>Request::getClientIp()
        ];
        Logs::create($data);
        return ['status'=>true];
    }

}