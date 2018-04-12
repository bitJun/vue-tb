<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/11/8
 * Time: 下午8:56
 */

namespace App\Services\User;


use App\Model\User;

class UserService
{
    public function getUser($id,$shop_id){
        $user = User::where(array('id' => $id, 'shop_id' => $shop_id))->first();
        if (!$user){
            return false;
        }
        return $user;
    }

}