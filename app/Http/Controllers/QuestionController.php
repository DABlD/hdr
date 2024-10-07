<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

use App\Helpers\Helper;

class QuestionController extends Controller
{
    public function __construct(){
        $this->table = "questions";
    }

    public function get(Request $req){
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

    public function store(Request $req){
        $temp = new Question();
        $temp->package_id = $req->package_id;
        $temp->category_id = $req->category_id;
        $temp->name = $req->name;
        $temp->type = $req->type;
        $temp->code = $req->code;
        $temp->save();

        Helper::log(auth()->user()->id, "created question for package #$req->package_id", $temp->id);

        echo "success";
    }

    public function update(Request $req){
        $result = Question::where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated question', $req->id);
    }

    public function delete(Request $req){
        Question::find($req->id)->delete();

        if(isset($req->category)){
            Question::where('category_id', $req->id)->delete();
            Helper::log(auth()->user()->id, 'deleted category', $req->id);
        }
        else{
            Helper::log(auth()->user()->id, 'deleted question', $req->id);
        }
    }

    public function index(){
        return $this->_view('index', [
            'title' => "Template Manager"
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
