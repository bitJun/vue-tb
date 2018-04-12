<?php

namespace App\Services\User;

use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function putPassword($password,$oldPassword)
    {
        $password = bcrypt($password);
        if(!Hash::check($oldPassword,Auth::user()->password))
        {
            return false;
        }
        $shop_id = Auth::user()->shop_id;
        $id = Auth::user()->id;
        if(User::where(array('id'=>$id,'shop_id'=>$shop_id))->update(array('password'=>$password)))
        {
            return true;
        }
        return false;
    }
}
