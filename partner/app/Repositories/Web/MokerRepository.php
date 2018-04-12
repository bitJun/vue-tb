<?php
/**
 * Created by PhpStorm.
 * User: yaobin
 * Date: 2017/12/18
 * Time: 下午4:36
 */

namespace App\Repositories\Web;


use App\Model\Moker\Moker;

class MokerRepository
{
    /**
     * 获取服务商辖区的商铺总数
     * @param $map
     * @return int
     */
    public function __getMokerCount($map)
    {
        $result = Moker::where($map)
            ->count();
        return $result ?: 0;
    }
}