<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvitationController extends Controller
{
    public function index($id)
    {
        return view('welcome',['invitee_moker_id'=>$id]);
    }
}
