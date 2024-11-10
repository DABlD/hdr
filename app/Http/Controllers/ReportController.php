<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

use App\Models\{ExamList, PatientPackage};

class ReportController extends Controller
{
    function exam(Request $req){
        $array = ExamList::select('exam_lists.*', 'p.company_name');

        $array->join('users as u', 'u.id', '=', 'exam_lists.user_id');
        $array->join('patients as p', 'p.user_id', '=', 'u.id');

        // FILTERS
        $filters = $req->filters;
        $array = $array->where(function($q) use($filters){
            if(isset($filters["fFname"])){
                $q->where('fname', 'LIKE', "%" . $filters["fFname"] . "%");
            }
            if(isset($filters["fLname"])){
                $q->where('lname', 'LIKE', "%" . $filters["fLname"] . "%");
            }
        });

        $array = $array->where(function($q) use($filters){
            if($filters["fCompany"] == ""){
                $q->whereNull('company_name');
            }
            else{
                $q->where('company_name', 'LIKE', $filters["fCompany"]);

                if($filters["fCompany"] == "%%"){
                    $q->orWhereNull('company_name');
                }
            }
        });

        $array = $array->where('exam_lists.type', $filters['fType']);
        $array = $array->where('exam_lists.created_at', 'like', $filters['fDate'] . '%');
        // END FILTERS

        $array = $array->get();
        $array->load('user.patient.exams');

        $filename = "$req->type - " . $filters['fDate'];

        $class = "App\\Exports\\Exam";

        return Excel::download(new $class($array, $req->type), "$filename.xlsx");
    }

    function packagesSold(Request $req){
        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, $req->to])
                ->where('package_id', '!=', 1)
                ->where('package_id', '!=', 2)
                ->where('p.company', 'like', $req->company)
                ->select('patient_packages.*', 'p.company')
                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                ->get();

        $data->load('user.patient');
        $data->load('package');

        $company = $req->company == "%%" ? "All Company" : preg_replace('/[^A-Za-z0-9\-]/', '', $req->company);

        $filename = "Packages Sold ($req->from - $req->to) ($company)";
        
        $class = "App\\Exports\\PackageSold";

        return Excel::download(new $class($data), "$filename.xlsx");
    }

    function sales(Request $req){
        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, $req->to])
                                ->where('package_id', '!=', 1)
                                ->where('package_id', '!=', 2)
                                ->where('p.company', 'like', $req->company)
                                ->select('patient_packages.*', 'p.company')
                                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                                ->get();
        
        $dates = $this->getDates($req->from, $req->to);
        $total = 0;
        $array = [];

        foreach($data as $request){
            $details = json_decode($request->details);
            $amount = $details->amount;

            if(isset($array[now()->parse($request->created_at)->toDateString()])){
                $array[now()->parse($request->created_at)->toDateString()] += $amount;
            }
            else{
                $array[now()->parse($request->created_at)->toDateString()] = $amount;
            }

            $total += $amount;
        }

        $company = $req->company == "%%" ? "All Company" : preg_replace('/[^A-Za-z0-9\-]/', '', $req->company);

        $filename = "Sales ($req->from - $req->to) ($company)";
        
        $class = "App\\Exports\\Sales";

        $data = [];
        $data['sales'] = $array;
        $data['dates'] = $dates;
        $data['total'] = $total;

        return Excel::download(new $class($data), "$filename.xlsx");
    }

    private function getDates($from, $to){
        $dates = [];

        while($from <= $to){
            $tempDate = now()->parse($from);
            array_push($dates, $tempDate->toDateString());
            $from = $tempDate->addDay()->toDateString();
        }

        return $dates;
    }
}
