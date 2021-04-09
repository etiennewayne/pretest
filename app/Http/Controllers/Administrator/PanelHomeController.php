<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelHomeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index(){
        return view('panel.panel-home');
    }



}
