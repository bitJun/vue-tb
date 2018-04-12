<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Session;

class TestController extends Controller
{
    public function clear()
    {
        Session::flush();
    }
}