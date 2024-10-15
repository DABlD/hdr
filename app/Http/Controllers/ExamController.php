<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Patient, Package, User};

class ExamController extends Controller
{
    public function ape(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('ape', [
            'title' => "APE",
            'companies' => $companies
        ]);
    }

    public function pee(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('pe', [
            'title' => "PE",
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("exams.$view", $data);
    }
}
