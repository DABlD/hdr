<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User};
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

        // IF HAS JOIN
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

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
}