<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PatientPackage, Patient, Package};

use App\Helpers\Helper;

// PDF CLASSES
use App\Exports\PDFExport;
use PDF;

class PatientPackageController extends Controller
{
    public function __construct(){
        $this->table = "patient_packages";
    }

    public function get(Request $req){
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

        echo json_encode($array);
    }

    public function store(Request $req){
        $patient = Patient::where('user_id', $req->uid)->first();

        foreach($req->packages as $pid){
            $package = Package::find($pid);

            $temp = new PatientPackage();
            $temp->user_id = $patient->user_id;
            $temp->patient_id = $patient->id;
            $temp->package_id = $package->id;
            $temp->type = $req->type;

            $temp->details = json_encode($package->toArray());
            // $temp->question_with_answers = $req->question_with_answers;
            $temp->save();

            Helper::log(auth()->user()->id, "bought package $req->package_id", $patient->id);
        }


        echo "success";
    }

    public function update(Request $req){
        $result = PatientPackage::where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated patient package', $req->id);
    }

    public function exportDocument(Request $req){
        $data = PatientPackage::find($req->id);

        $data->load('user.patient');
        $data->load('package');

        $fn = "RI" . now()->format('Ymd') . '-' . $data->user->lname . '_' . $data->user->fname;

        $pdf = new PDFExport($data, $fn, "impressions");
        // $pdf->getData();
        return $pdf->download();
    }

    public function index(){
        return $this->_view('index', [
            'title' => "Patient Packages"
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
