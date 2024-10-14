<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, PatientPackage};

class DashboardController extends Controller
{
    function index(){
        $users = User::where('role', 'Patient')->count();
        $companies = User::where('role', 'Company')->count();

        $pps = PatientPackage::all();

        $ppcount = $pps->count();
        $total = 0;

        foreach($pps as $pp){
            $temp = json_decode($pp->details);
            $total += $temp->amount;
        }

        return $this->_view('dashboard', [
            'title'     => 'Dashboard',
            'users'     => $users,
            'companies' => $companies,
            'ppcount'   => $ppcount,
            'total'     => $total
        ]);
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
