<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\{EmployeeImport};

class CompanyController extends Controller
{
    public function dashboard(Request $req){
        $company = base64_decode($req->code);

        return $this->_view('company.dashboard', [
            'title' => $company
        ]);
    }

    public function import(Request $req){
        Excel::import(new EmployeeImport($req->company), $req->file('excel'));
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
