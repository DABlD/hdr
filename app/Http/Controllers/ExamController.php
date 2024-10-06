<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Patient, Package};

class ExamController extends Controller
{
    public function ape(){
        return $this->_view('ape', [
            'title' => "APE"
        ]);
    }

    public function pee(){
        return $this->_view('pe', [
            'title' => "PE"
        ]);
    }

    private function _view($view, $data = array()){
        return view("exams.$view", $data);
    }
}
