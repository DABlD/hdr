<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

use App\Models\ExamList;

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
}
