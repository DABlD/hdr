<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Patient, Package};

class ExamController extends Controller
{
    public function ape(){
        $companies = Patient::pluck('company_name')->unique();

        return $this->_view('ape', [
            'title' => "APE",
            'companies' => $companies
        ]);
    }

    public function pee(){
        $companies = Patient::pluck('company_name')->unique();

        return $this->_view('pe', [
            'title' => "PE",
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("exams.$view", $data);
    }
}
