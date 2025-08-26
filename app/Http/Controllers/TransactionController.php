<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function index(){
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
