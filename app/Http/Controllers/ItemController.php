<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

use App\Helpers\Helper;

class ItemController extends Controller
{
    public function __construct(){
        $this->table = "items";
    }

    public function get(Request $req){
        $array = Item::select($req->select);

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
        $temp->code = $req->code;
        $temp->category = $req->category;
        $temp->brand = $req->brand;
        $temp->name = $req->name;
        $temp->packaging = $req->packaging;
        $temp->amount = $req->amount;
        $temp->reorder = $req->reorder;
        $temp->stock = $req->stock;
        $temp->save();

        Helper::log(auth()->user()->id, "created item", $temp->id);

        echo "success";
    }

    public function update(Request $req){
        $result = Item::where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated item', $req->id);
    }

    public function delete(Request $req){
        Item::find($req->id)->delete();
        Helper::log(auth()->user()->id, 'deleted item', $req->id);
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