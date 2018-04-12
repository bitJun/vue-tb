<?php
namespace App\Http\Controllers\Api\Order;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use TaoTui\Cashier\Facades\Llpay;


class TestController extends Controller
{

    public function test($type,$id,Request $request){
        //申请提现
        if($type == 'cashoutcombineapply'){
            $data =array(
                'user_id' => '1',
                'mobile' => '15157172104',
                'pwd_pay' => 'taotui2017',
                'dt_order' => time(),
                'no_order' => '20171014403036208652',
                'money_order' => '0.1',
                'created_at' => time('YmdHis'),
                'card_no' => '6212261202021744165',
                'type_register' => 1
            );
            return Llpay::cashoutcombineapply($data);

        }elseif($type == 'orderquery'){
            $data =array(
                'no_order' => '201711138604262564',
                'type_dc' => '1',
            );
            return Llpay::orderquery($data);
        }elseif($type == 'modiyunituseracct'){
            $data =array(
                'user_id' => '36',
                'mobile' => '15058120111',
                'token' => '02FDE71335544A02696C41B8D046BDE9',
                'city_code' => '330100',
                'brabank_name' => '中国工商银行杭州艮山支行',
                'card_no' => '1202022309900371775',
                'bank_code' => '01020000',
                'acct_name' => '杭州微奉科技有限公司',
                'created_at' => time('YmdHis'),
            );
            return Llpay::modiyunituseracct($data);

        }elseif($type == 'pwdauth'){
            //$newEncrypter = new \Illuminate\Encryption\Encrypter( md5('modian'), Config::get( 'app.cipher' ) );
            //$decrypted = $newEncrypter->decrypt('eyJpdiI6IkYzQzFpN1A0cHhoN1wvZ3pybVhzaU53PT0iLCJ2YWx1ZSI6IjN6XC9JN0Z3UDNJMzZZRFRhVXJtalhBPT0iLCJtYWMiOiI4NzQ3MzYzYWE5NGFkMmQ2YmM5YjZlODkzNWFhMjFhZjU4YTkyZmEzMjZhZDFlYzViNzJhMWZmNjE2NDM1ZGU3In0=');
            //钱包支付密码验证授权接口
            $data =array(
                'user_id' => '36',
                'mobile' => '15058120111',
                'pwd_pay' => 'zcd258',
                'flag_check' => '0',
                'num_license' => '330104000273110',
                'created_at' => time('YmdHis')
            );
            $data['addr_pro'] = '330000';
            $data['addr_city'] = '330100';
            return Llpay::pwdauth($data);
        }elseif($type == 'modifyusermob'){
            $data['user_id'] = '100000021';
            $data['mobile'] = '15968120111';
            $data['mob_bind'] = '15988171222';
            $data['token'] ='894F722715F7F4981F36F9EF5D7EB9DD';
            $data['created_at'] = time('YmdHis');
            return Llpay::modifyusermob($data);

        }elseif ($type == 'traderpayment'){
            $data['col_userid'] = 1;
            $data['money_order'] = 0.1;
            $data['mobile'] ='15157172104';
            $data['created_at'] = time('YmdHis');
            $data['order_sn'] = '20171014403036208652';
            return Llpay::traderpayment($data);

        }elseif($type == 'singleuserquery'){
            $data =array(
                'user_id' => $id,
            );
            return Llpay::singleuserquery($data);
        }elseif ($type == 'combinepay'){
            $data =array(
                'user_id' => '1',
                'mobile' => '15257113820',
                'order_sn' => 'E2017102308313995291',
                'amount' => '0.11',
                'title' => '测试',
                'money_order' => '0.01',
                'created_at' => time('YmdHis'),
                'pay_type' => 'W',
                'appid' => 'wx50c50fa6fe678a97',
                'openid' => 'ouDB11d49tVFOGNzE52_6_CPIQ1Y'
            );
            return Llpay::combinepay($data);

            //绑卡查询
        }elseif ($type == 'userbankcard'){
            $data =array(
                'user_id' => $id,
            );
            return Llpay::userbankcard($data);
        }elseif($type == 'opensmsunituser'){
            //企业用户注册
            $data =array(
                'user_id' =>  '1',
                'mobile' => '15157172104',
                'created_at' => time('YmdHis'),
                'token' => '3D0D62E95E54F31302BD59B2E0355A30',
                'name_user' => '李长勇',
                'exp_idcard' => '20171113', //法人身份证有效期
                'type_expidcard' => '0',
                'no_idcard' => '422801198709181213', //法人证件号
                'pwd_pay' => 'taotui54',
                'addr_unit' => '浙江省杭州市西湖区翠柏路7号电子商务产业园',   //企业地址
                'addr_pro' => '330000',
                'addr_city' => '330100',
                'addr_dist' => '330108', //区
                'busi_user' => '食品',   //经营范围
                'name_unit' => '魔店',
                'num_license' => '4403036208652', //营业执照号码
                'type_register' => '1',   //企业注册类型
                'type_license' => '2',  //营业执照类型
                'exp_license'   => '20250321',//营业执照有效期
                'type_explicense' => '1',  //营业执照有效期类型

                //'org_code' => '92320104MA1Q4AYY66',  //组织机构代码
                //'exp_orgcode'  =>   '20190823' ,    //组织机构代码有效期

                //'city_code' => '330100',
                //'brabank_name' => '中国工商银行',
                //'card_no' => '6212261202021744165',
                //'bank_code' => '01020000',    //中国工商银行编码
            );
            return Llpay::opensmsunituser($data);
        }elseif($type == 'smssend'){
            //发送验证码
            $data =array(
                'user_id' =>  '1',
                'mobile' => '15157172104',
                'num_license' => '4403036208652',
                'flag_send' => '1',
            );
            return Llpay::smssend($data);
        }elseif($type == 'smscheck'){
            //验证短信验证码,获取token
            $data =array(
                'user_id' => '15157172104',
                'token' => '948293F36520B96DF619CB6EC40DAE9B',
                'verify_code' => '915961',
            );
            return Llpay::smscheck($data);
        }else{
            //上传照片
            $data = $request->all();
            $data['user_id'] = '15257113820';
            $result = Llpay::uploadunitphoto($data);
            return $result;

            //个体工商户解绑银行卡
            $data =array(
                'user_id' => '15257113820',
                'no_agree' => '2017101232257633',
                'pwd_pay' => 'taotui54',
            );
            return Llpay::bankcardunbind($data);

            //个体工商户银行卡绑卡验证接口
            $data =array(
                'user_id' => '15257113820',
                'verify_code' => '978771',
                'token' => '277A7FAABA3EA66556085F09C3F42F56',
            );
            return Llpay::bankcardauthverfy($data);

            //个体工商户绑卡
            $data =array(
                'user_id' => '15257113820',
                'mobile' => '15257113820',
                'pay_type' => '2',
                'card_no' => '6212261202012334430',
                'bind_mob' => '15257113820',
                'created_at' => time('YmdHis')
            );
            $data['addr_pro'] = '330000';
            $data['addr_city'] = '330100';
            return Llpay::bankcardopenauth($data);
        }
    }

    public function upload(){
        if (empty($_FILES['front_card'])) {
            $return = array('status' => 0, 'info' => '没有上传指定名称的文件');
        } else {
            // 保存文件
            $file = $_FILES['front_card'];

            // 重命名文件,便于识别
            $base_name = explode('.', $file['name']);
            $base_name[0] .= '_upload_var_curl';
            $base_name = implode('.', $base_name);
            $file_name = __DIR__ . "/{$base_name}";

            if (move_uploaded_file($file['tmp_name'], $file_name)) {
                // 本地测试时,可能需要更改下面的URL
                $url    = "http://localhost/test/{$base_name}";
                $return = array('status' => 1, 'info' => '上传成功', 'data' => array('url' => $url));
            } else {
                $return = array('status' => 0, 'info' => '上传失败');
            }
        }

        return array('return' => $return,'file_name' => $file_name);
        exit(json_encode($return));

    }

}