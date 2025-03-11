<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PatientPackage, Patient, Package, Question, ExamList, Setting};

use App\Helpers\Helper;
use Image;

// EXCEL
use Maatwebsite\Excel\Facades\Excel;

// PDF CLASSES
use App\Exports\PDFExport;
use PDF;
use File;

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

        if($req->mhr){
            $package = $array->first();

            $questions = null;

            $mhr = PatientPackage::select('*')->where('user_id', $package->user_id)->where('package_id', 2)->get();
            if($mhr){
                $mhr = $mhr->first();
                $mhr->load('package');

                $questions = Question::where("package_id", 2)->get()->groupBy("category_id");
            }

            $array = ["package" => $package, "mhr" => $mhr, "questions" => $questions];
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

            $temp->c_remarks = $req->c_remarks;

            $temp->details = json_encode($package->toArray());
            // $temp->question_with_answers = $req->question_with_answers;
            $temp->save();

            Helper::log(auth()->user()->id, "bought package $req->package_id", $patient->id);
        }


        echo "success";
    }

    public function update(Request $req){
        if($req->hasFile('files')){
            $patientPackage = PatientPackage::find($req->id);
            $patientPackage->load('user');
            $uname = $patientPackage->user->lname . '_' . $patientPackage->user->fname;

            $files = $patientPackage->file ? json_decode($patientPackage->file) : [];

            foreach ($req->file('files') as $file) {
                $name = $file->getClientOriginalName();
                $path = "uploads/PP$req->id/";
                File::isDirectory($path) or File::makeDirectory($path, 0775, true, true);
                $file->move($path, $name);

                if(!in_array($path . $name, $files)){
                    array_push($files, $path . $name);
                }
            }

            $patientPackage->file = json_encode($files);
            $patientPackage->save();
            Helper::log(auth()->user()->id, 'updated patient package', $req->id);

            echo json_encode($files);
        }
        else{
            $result = PatientPackage::where('id', $req->id)->update($req->except(['id', '_token']));
            echo Helper::log(auth()->user()->id, 'updated patient package', $req->id);
        }
    }

    public function deleteFile(Request $req){
        $patientPackage = PatientPackage::find($req->id);
        $files = json_decode($patientPackage->file);
        $temp = [];

        foreach($files as $file){
            if($file != $req->filename){
                array_push($temp, $file);
            }
            else{
                File::delete($file);
            }
        }

        $patientPackage->file = json_encode($temp);
        $patientPackage->save();
        echo true;
    }

    public function exportInvoice(Request $req){
        $data = PatientPackage::find($req->id);

        $data->doctor_id = ExamList::where('user_id', $data->user_id)->orderBy('updated_at', 'desc')->first()->doctor_id;

        $data->load('user.patient');
        $data->load('package');

        $fn = "OHN-INV-" . str_pad($data->id, 9, '0', STR_PAD_LEFT) . '-' . $data->user->lname . '_' . $data->user->fname;

        $pdf = new PDFExport($data, $fn, "invoice");
        return $pdf->invoice();
    }

    public function exportDocument(Request $req){
        $settings = Setting::pluck('value', 'name');
        $data = PatientPackage::find($req->id);

        $data->load('user.patient');
        $data->load('package');

        $pmr = PatientPackage::where('user_id', $data->user_id)->where('package_id', 2)->first();
        $answers = [];

        // FOR DEBUGGING
        $idCheck = null;
        // $idCheck = 130;

        foreach(json_decode($pmr->question_with_answers) as $answer){
            if($idCheck && $answer->id == $idCheck){
                dd($answer);
            }

            if(isset($answer->answer)){
                $answers[$answer->id]["answer"] = $answer->answer;
                $answers[$answer->id]["remark"] = $answer->remark;
            }
        }

        $questions = Question::where('package_id', $pmr->package_id)->get()->groupBy('category_id');

        $data->questions = $questions->toArray();
        $data->answers = $answers;

        $fn = "RI" . now()->format('Ymd') . '-' . $data->user->lname . '_' . $data->user->fname;

        // $pdf = new PDFExport($data, $fn, "impressions");
        // $pdf->getData();
        // return $pdf->report();

        $class = "App\\Exports\\Impression";

        Excel::store(new $class($data, $settings), "/public/$fn.pdf");
        return "/storage/$fn.pdf";
    }

    public function delete(Request $req){
        PatientPackage::find($req->id)->delete();
        Helper::log(auth()->user()->id, 'deleted patient package', $req->id);
    }

    public function index(){
        return $this->_view('index', [
            'title' => "Patient Packages",
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
