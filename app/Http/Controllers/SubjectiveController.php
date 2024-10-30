<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Package, PatientPackage, Question};

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

    public function getPatientPackage(Request $req){
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

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', "$this->table.id");
        }

        $array = $array->get();

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

        if($req->mhr){
            $package = $array->first();

            $questions = null;

            $mhr = PatientPackage::select('*')->where('user_id', $package->user_id)->where('package_id', 2)->get();
            if($mhr){
                $mhr = $mhr->first();
                $mhr->load('package');

                $questions = Question::where("package_id", 2)->get()->groupBy("category_id");
            }

            $array = ["package" => $package, "mhr" => $mhr, "questions" => $questions];
        }

        echo json_encode($array);
    }

    public function getQuestion(Request $req){
        $array = Question::select($req->select);

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

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', "$this->table.id");
        }

        $array = $array->get();

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

    public function update(Request $req){
        $result = PatientPackage::where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log($req->id, 'was answered', $req->id);
    }

    private function _view($view, $data = array()){
        return view("patients.subjective", $data);
    }
}
