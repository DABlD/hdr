<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Package, PatientPackage};

class SubjectiveController extends Controller
{
    public function index(Request $req){
        $user = User::find($req->id);
        $user->load('patient');

        return $this->_view('index', [
            'title' => "Fill-up Info",
            'user' => $user
        ]);
    }

    private function _view($view, $data = array()){
        return view("patients.subjective", $data);
    }
}
