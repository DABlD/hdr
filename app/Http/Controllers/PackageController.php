<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Package, User};

use App\Helpers\Helper;
use DB;

class PackageController extends Controller
{
    public function __construct(){
        $this->table = "packages";
    }

    public function get(Request $req){
        $array = Package::select($req->select);

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

        if($req->getLaboratory){
            $array = $array->orWhere('type', 'Laboratory');
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

    public function getCompanies(){
        echo User::where('role', 'Company')->distinct()->pluck('company');
        // echo Package::distinct()->pluck('company');
    }

    public function store(Request $req){
        $temp = new Package();

        $temp->name = $req->name;
        $temp->amount = $req->amount;
        $temp->type = $req->type;
        $temp->company = $req->company;
        $temp->save();

        Helper::log(auth()->user()->id, "created package", $temp->id);

        echo "success";
    }

    public function update(Request $req){
        $result = DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated question', $req->id);
    }

    public function delete(Request $req){
        Package::find($req->id)->delete();
        Helper::log(auth()->user()->id, 'deleted question', $req->id);
    }
}