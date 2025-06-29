<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ExamList};

use App\Helpers\Helper;

class ExamListController extends Controller
{
    public function __construct(){
        $this->table = "exam_lists";
    }

    public function get(Request $req){
        $array = ExamList::select($req->select);

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
        $data = new ExamList();
        $data->type = $req->type;
        $data->user_id = $req->user_id;
        $data->queued_doctors = "[]";
        $data->queued_dates = "[]";
        $data->save();

        Helper::log(auth()->user()->id, "added user for $req->type", $req->user_id);

        echo $data->id;
    }

    public function update(Request $req){
        // ExamList::where('id', $req->id)->update($req->except(['id', '_token']));

        $el = ExamList::find($req->id);

        if(isset($req->deleted_at)){
            $el->delete();
        }
        else{
            $el->doctor_id = $req->doctor_id;
            $temp = json_decode($el->queued_doctors);
            array_push($temp, $req->doctor_id);
            $el->queued_doctors = json_encode($temp);
            
            $el->queued_at = now();
            $temp = json_decode($el->queued_dates);
            array_push($temp, now()->format('Y-m-d'));
            $el->queued_dates = json_encode($temp);
        }

        $el->save();

        echo Helper::log(auth()->user()->id, 'updated Exam List', $req->id);
    }

    public function index(){
        return $this->_view('index', [
            'title' => "Queued Patients",
        ]);
    }

    private function _view($view, $data = array()){
        return view("exams.$view", $data);
    }
}
