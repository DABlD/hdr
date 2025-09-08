<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Patient, User, PatientPackage, Package};
use Image;
use DB;

use App\Helpers\Helper;

class PatientController extends Controller
{
    public function __construct(){
        $this->table = "patients";
    }

    public function get(Request $req){
        $array = Patient::select($req->select);

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
            $array->load($req->load);
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function store(Request $req){
        $user = new User();

        if($req->hasFile('avatar')){
            $temp = $req->file('avatar');
            $image = Image::make($temp);

            $name = $req->lname . '_' . $req->fname . '-' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/' . env('UPLOAD_URL'));

            $image->resize(250, 250);
            $image->save($destinationPath . $name);
            $user->avatar = 'uploads/' . $name;
        }

        $user->role = "Patient";
        $user->username = $req->email ?? substr($req->fname, 0, 1) . $req->lname . now()->format('m-d');
        $user->password = "12345678";
        $user->prefix = strtoupper($req->prefix);
        $user->fname = strtoupper($req->fname);
        $user->mname = strtoupper($req->mname);
        $user->lname = strtoupper($req->lname);
        $user->suffix = strtoupper($req->suffix);
        $user->birthday = $req->birthday;
        $user->birth_place = $req->birth_place;
        $user->gender = $req->gender;
        $user->civil_status = $req->civil_status;
        $user->nationality = $req->nationality;
        $user->religion = $req->religion;
        $user->contact = $req->contact;
        $user->email = $req->email;
        $user->address = $req->address;
        $user->save();

        $ctr = Patient::where('created_at', 'like', now()->format('Y-m-d') . '%')->count();

        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->patient_id = "P" . now()->format('ymd') . str_pad($ctr+1, 5, '0', STR_PAD_LEFT);
        $patient->hmo_provider = $req->hmo_provider;
        $patient->hmo_number = $req->hmo_number;
        // $patient->mothers_name = $req->mothers_name;
        // $patient->fathers_name = $req->fathers_name;
        // $patient->guardian_name = $req->guardian_name;
        $patient->employment_status = $req->employment_status;
        $patient->company_name = $req->company_name;
        $patient->company_position = $req->company_position;
        $patient->company_contact = $req->company_contact;
        $patient->sss = $req->sss;
        $patient->tin_number = $req->tin_number;
        $patient->save();

        $user->username = $patient->patient_id;
        $user->save();

        Helper::log(auth()->user()->id, 'created patient', $user->id);

        $package = Package::find(2);

        $temp = new PatientPackage();
        $temp->user_id = $patient->user_id;
        $temp->patient_id = $patient->id;
        $temp->package_id = $package->id;
        $temp->type = "PEE";

        $temp->details = json_encode($package->toArray());
        // $temp->question_with_answers = $req->question_with_answers;
        $temp->save();

        Helper::log(auth()->user()->id, "bought package $req->package_id", $patient->id);

        echo "success";
    }

    public function update(Request $req){
        $result = DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));

        echo Helper::log(auth()->user()->id, 'updated patient', $req->id);
    }

    public function delete(Request $req){
        User::find($req->id)->delete();
        Helper::log(auth()->user()->id, 'deleted patient', $req->id);
    }

    public function index(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('index', [
            'title' => ucfirst($this->table),
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
