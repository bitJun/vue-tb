<?php
/**
 * Created by Sublime.
 * User: 胥亚龙(xx4562001@gmail.com)
 * Date: 16/4/22
 * Time: 上午12:07
 */

namespace App\Utils;

use GuzzleHttp\Client;

class Gaode {

    private $url = "http://restapi.amap.com/v3/geocode/geo?";
    //private $key = "a6dbfbd1593500d6dbb30ef3c1303885";  //app应用对应的key
    //private $private_key = "d3ca2002619276f8c4e6ff1beaeb38a6";  //私钥
    private $key = "954774a78c4a55d72e5f5674400ac9ff";  //app应用对应的key
    private $private_key = "413751038373cb3eb27f2aea75f527ce";  //私钥

    /**
     *
     * User: lufee(ldw1007@sina.cn)
     * @param(
     *     name="address",
     *     description="结构化地址信息 规则遵循：国家、省份、城市、区县、城镇、乡村、街道、门牌号码、屋邨、大厦，如：北京市朝阳区阜通东大街6号。如果需要解析多个地址的话，请用"|"进行间隔，并且将 batch 参数设置为 true，最多支持 10 个地址进进行"|"分割形式的请求。",
     *     required=true,
     *     type="string"
     * ),
     * @param(
     *     name="city",
     *     description="指定查询的城市 可选输入内容包括：指定城市的中文（如北京）、指定城市的中文全拼（beijing）、citycode（010）、adcode（110000）。当指定城市查询内容为空时，会进行全国范围内的地址转换检索。",
     *     required=true,
     *     type="string"
     * )
     */
    public function geoCode($address,$city="")
    {
        $params = [
            'key' => $this->key,
            'address' => $address,
            'city' => $city
        ];

        $params = $this->_para_filter($params);
        ksort($params);
        $paramStr = '';
        foreach ($params as $key=>$v)
        {
            $paramStr .= $key."=".$v."&";
        }

        $paramStr = substr($paramStr,0,-1);
        $sign = md5($paramStr.$this->private_key);

        $paramStr .= "&sig=".$sign;

        //$client = new Client();
        //$result = $client->get($this->url.$paramStr,[])->getBody()->getContents();

        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $this->url.$paramStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $result = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        return $result;
    }

    /**
     * 参数过滤
     * @param $para
     * @return array
     */
    private function _para_filter($para) {
        $para_filter = array();
        foreach ($para as $key => $val) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }
}