<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Nurse, User};
use DB;

use App\Helpers\Helper;

class NurseController extends Controller
{
    public function __construct(){
        $this->table = "nurses";
    }

    public function get(Request $req){
        $array = Nurse::select($req->select);

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
        $nurse = new Nurse();

        $nurse->user_id = $req->user_id;
        $nurse->doctor_id = $req->doctor_id;
        $nurse->sss = $req->sss;
        $nurse->tin = $req->tin;
        $nurse->philhealth = $req->philhealth;
        $nurse->pagibig = $req->pagibig;

        $nurse->save();

        Helper::log(auth()->user()->id, 'created nurse', $user->id);

        echo "success";
    }

    public function update(Request $req){
        $result = DB::table($this->table)->where('user_id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated nurse', $req->id);
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
