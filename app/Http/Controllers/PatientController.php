<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Patient, User};
use DB;

class PatientController extends Controller
{
    public function __construct(){
        $this->table = "patients";
    }

    public function get(Request $req){
        $array = Patient::select($req->select);

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

    public function store(Request $req){
        $user = new User();
        $user->role = "Patient";
        $user->username = $req->email;
        $user->password = "12345678";
        $user->fname = $req->fname;
        $user->mname = $req->mname;
        $user->lname = $req->lname;
        $user->birthday = $req->birthday;
        $user->birth_place = $req->birth_place;
        $user->gender = $req->gender;
        $user->civil_status = $req->civil_status;
        $user->nationality = $req->nationality;
        $user->religion = $req->religion;
        $user->contact = $req->contact;
        $user->email = $req->email;
        $user->address = $req->address;
        $user->save();

        $ctr = Patient::where('created_at', 'like', now()->format('Y-m-d') . '%')->count();

        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->patient_id = "P" . now()->format('ymd') . str_pad($ctr+1, 5, '0', STR_PAD_LEFT);
        $patient->hmo_provider = $req->hmo_provider;
        $patient->hmo_number = $req->hmo_number;
        $patient->save();

        $user->username = $patient->patient_id;
        $user->save();

        echo "success";
    }

    public function update(Request $req){
        echo DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));
    }

    public function updatePassword(Request $req){
        $user = User::find($req->id);
        $user->password = $req->password;
        $user->save();
    }

    public function delete(Request $req){
        User::find($req->id)->delete();
    }

    public function index(){
        return $this->_view('index', [
            'title' => ucfirst($this->table)
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
