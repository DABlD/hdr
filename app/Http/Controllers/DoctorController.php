<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Doctor, User};
use Image;
use DB;

use App\Helpers\Helper;

class PatientController extends Controller
{
    public function __construct(){
        $this->table = "doctors";
    }

    public function get(Request $req){
        $array = Doctor::select($req->select);

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
        $doctor = new Doctor();

        if($req->hasFile('signature')){
            $temp = $req->file('signature');
            $image = Image::make($temp);

            $name = $req->lname . '_' . $req->fname . '-sig-' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');

            $image->resize(250, 250);
            $image->save($destinationPath . $name);
            $doctor->signature = 'uploads/' . $name;
        }

        $doctor->user_id = $req->user_id;
        $doctor->sss = $req->sss;
        $doctor->tin = $req->tin;
        $doctor->philhealth = $req->philhealth;
        $doctor->license_number = $req->license_number;
        $doctor->s2_number = $req->s2_number;
        $doctor->ptr = $req->ptr;
        $doctor->specialization = $req->specialization;
        $doctor->pharma_partner = $req->pharma_partner;
        $doctor->title = $req->title;
        $doctor->medical_association = $req->medical_association;
        $doctor->diplomate = $req->diplomate;

        $doctor->save();

        Helper::log(auth()->user()->id, 'created doctor', $user->id);

        echo "success";
    }

    public function update(Request $req){
        $result = DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated doctor', $req->id);
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
