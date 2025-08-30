<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction, User};

use App\Helpers\Helper;

class TransactionController extends Controller
{
    public function __construct(){
        $this->table = "transactions";
    }

    public function get(Request $req){
        $array = Transaction::select($req->select);

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
        $temp = new Transaction();
        $temp->company = $req->company;
        $temp->package_id = $req->package_id;
        $temp->pax = $req->pax;
        $temp->pending = $req->pax;
        $temp->pp_ids = json_encode([]);
        $temp->save();

        Helper::log(auth()->user()->id, "added transaction for $req->company", $req->package_id);

        echo "success";
    }

    public function update(Request $req){
        $result = Transaction::where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated question', $req->id);
    }

    public function delete(Request $req){
        Transaction::find($req->id)->delete();
        Helper::log(auth()->user()->id, 'deleted transaction', $req->id);
    }

    public function index(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('index', [
            'title' => "Transactions",
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
