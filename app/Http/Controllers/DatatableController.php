<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Patient, PatientPackage, ExamList};
use DB;

class DatatableController extends Controller
{
    public function user(Request $req){
        $array = User::select($req->select);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS WHERE2
        if($req->where2){
            $array = $array->where($req->where2[0], isset($req->where2[2]) ? $req->where2[1] : "=", $req->where2[2] ?? $req->where2[1]);
        }

        // IF HAS WHERE3
        if($req->where3){
            $array = $array->where($req->where3[0], isset($req->where3[2]) ? $req->where3[1] : "=", $req->where3[2] ?? $req->where3[1]);
        }

        // IF HAS WHERE4
        if($req->where4){
            $array = $array->where($req->where4[0], isset($req->where4[2]) ? $req->where4[1] : "=", $req->where4[2] ?? $req->where4[1]);
        }

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 0,1);
            $array = $array->join("$req->join as $alias", "$alias.user_id", '=', 'users.id');
        }

        if(isset($req->filters)){
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
        }

        $array = $array->get();

        // FOR ACTIONS
        if(isset($req->filters) && sizeof($req->filters) > 1){
            foreach($array as $item){
                $item->medical = $item->medical;
            }
        }
        else{
            foreach($array as $item){
                $item->actions = $item->actions;
            }
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }

    public function examinees(Request $req){
        $array = ExamList::select($req->select);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 0,1);
            $array = $array->join("$req->join as $alias", "$alias.user_id", '=', 'users.id');
        }

        $array->join('users as u', 'u.id', '=', 'exam_lists.user_id');
        $array->join('patients as p', 'p.user_id', '=', 'u.id');

        $array->whereNull('u.deleted_at');

        if(isset($req->filters)){
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
        }

        $array = $array->get();

        // FOR ACTIONS
        if(isset($req->filters) && sizeof($req->filters) > 1){
            foreach($array as $item){
                if(auth()->user()->role != "Doctor")
                {
                    $medical = $item->user->medical;

                    if(in_array(auth()->user()->role, ["Admin", "Receptionist"])){
                        $medical .=   "<a class='btn btn-warning' data-toggle='tooltip' title='Assigned Doctor' onClick='assignedDoctor($item->id)'>" .
                                        "<i class='fas fa-user-doctor'></i>" .
                                    "</a>&nbsp;";
                    }
                    $medical .=   "<a class='btn btn-danger' data-toggle='tooltip' title='Delete' onClick='deleteType($item->id)'>" .
                                    "<i class='fas fa-trash'></i>" .
                                "</a>&nbsp;";
                }
                else{
                    $medical =  "<a class='btn btn-primary' data-toggle='tooltip' title='Medical Evaluation' onClick='evaluation($item->user_id)'>" .
                                    "<i class='fas fa-clipboard-prescription'></i>" .
                                "</a>&nbsp;";
                    $medical .=  "<a class='btn btn-info' data-toggle='tooltip' title='Request List' onClick='requestList($item->user_id)'>" .
                                    "<i class='fas fa-list'></i>" .
                                "</a>&nbsp;";
                }

                $item->status = PatientPackage::where('user_id', $item->user_id)->latest()->first()->status;
                $item->medical = $medical;
            }
        }
        else{
            foreach($array as $item){
                $item->actions = $item->actions;
            }
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function patient(Request $req){
        $array = Patient::select("patients.*", 'u.deleted_at');

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS WHERE2
        if($req->where2){
            $array = $array->where($req->where2[0], isset($req->where2[2]) ? $req->where2[1] : "=", $req->where2[2] ?? $req->where2[1]);
        }

        // IF HAS WHERE3
        if($req->where3){
            $array = $array->where($req->where3[0], isset($req->where3[2]) ? $req->where3[1] : "=", $req->where3[2] ?? $req->where3[1]);
        }

        $array = $array->join("users as u", "u.id", '=', 'patients.user_id');
        $array = $array->whereNull('u.deleted_at');

        if(isset($req->filters)){
            $filters = $req->filters;

            $array = $array->where(function($q) use($filters){
                $q->where('fname', 'LIKE', $filters["fFname"]);
                $q->where('lname', 'LIKE', $filters["fLname"]);
            });
        }

        $array = $array->get();

        // FOR ACTIONS
        if(isset($req->filters)){
            foreach($array as $item){
                $item->medical = $item->medical;
            }
        }
        else{
            foreach($array as $item){
                $item->actions = $item->actions;
            }
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function patientPackage(Request $req){
        $array = PatientPackage::select($req->select);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS WHERE2
        if($req->where2){
            $array = $array->where($req->where2[0], isset($req->where2[2]) ? $req->where2[1] : "=", $req->where2[2] ?? $req->where2[1]);
        }

        // IF HAS WHERE3
        if($req->where3){
            $array = $array->where($req->where3[0], isset($req->where3[2]) ? $req->where3[1] : "=", $req->where3[2] ?? $req->where3[1]);
        }

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 0, 1);
            $array = $array->join("$req->join as $alias", "$alias.id", '=', 'user_id');
        }

        if(isset($req->filters)){
            $filters = $req->filters;

            $array = $array->where(function($q) use($filters){
                $q->where('u.fname', 'LIKE', $filters["fFname"]);
                $q->where('u.lname', 'LIKE', $filters["fLname"]);
            });
        }

        $array = $array->get();

        // FOR ACTIONS
        if(isset($req->filters)){
            foreach($array as $item){
                $item->medical = $item->patient->medical;
            }
        }
        else{
            foreach($array as $item){
                $item->actions = $item->actions;
            }
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }
}