<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Doctor, Nurse, Setting, Receptionist, Imaging, Laboratory};
use DB;
use Auth;
use Image;

use App\Helpers\Helper;

class UserController extends Controller
{
    public function __construct(){
        $this->table = "users";
    }

    public function get(Request $req){
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

    public function get2(Request $req){
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
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', "$this->table.id");
        }


        $array = $array->first();

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        if($array->role == "Nurse"){
            $array->details = $array->nurse;
        }
        elseif($array->role == "Doctor" || $array->role == "Admin"){
            $array->details = $array->doctor;
        }
        elseif($array->role == "Receptionist"){
            $array->details = $array->receptionist;
        }
        elseif($array->role == "Imaging"){
            $array->details = $array->imaging;
        }
        elseif($array->role == "Laboratory"){
            $array->details = $array->laboratory;
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function store(Request $req){
        $data = new User();
        $data->username = $req->username;
        $data->fname = $req->fname;
        $data->mname = $req->mname;
        $data->lname = $req->lname;
        $data->role = $req->role;
        $data->email = $req->email;
        $data->birthday = $req->birthday;
        $data->gender = $req->gender;
        $data->address = $req->address;
        $data->contact = $req->contact;
        $data->password = $req->password;
        $temp = $data->save();

        Helper::log(auth()->user()->id, 'created user', $data->id);
        
        if($data->role == "Doctor"){
            Doctor::create([
                "user_id" => $data->id,
                "sss" => $req->sss,
                "tin" => $req->tin,
                "philhealth" => $req->philhealth,
                "pagibig" => $req->pagibig
            ]);

            Helper::log(auth()->user()->id, 'created doctor', $data->id);
        }
        elseif($data->role == "Nurse"){
            Nurse::create([
                "user_id" => $data->id,
                "sss" => $req->sss,
                "tin" => $req->tin,
                "philhealth" => $req->philhealth,
                "pagibig" => $req->pagibig,
            ]);

            Helper::log(auth()->user()->id, 'created nurse', $data->id);
        }
        elseif($data->role == "Receptionist"){
            Receptionist::create([
                "user_id" => $data->id,
                "sss" => $req->sss,
                "tin" => $req->tin,
                "philhealth" => $req->philhealth,
                "pagibig" => $req->pagibig,
            ]);

            Helper::log(auth()->user()->id, 'created receptionist', $data->id);
        }
        elseif($data->role == "Imaging"){
            Imaging::create([
                "user_id" => $data->id,
                "sss" => $req->sss,
                "tin" => $req->tin,
                "philhealth" => $req->philhealth,
                "pagibig" => $req->pagibig,
            ]);

            Helper::log(auth()->user()->id, 'created imaging', $data->id);
        }
        elseif($data->role == "Laboratory"){
            Laboratory::create([
                "user_id" => $data->id,
                "sss" => $req->sss,
                "tin" => $req->tin,
                "philhealth" => $req->philhealth,
                "pagibig" => $req->pagibig,
            ]);

            Helper::log(auth()->user()->id, 'created laboratory', $data->id);
        }

        echo $temp;
    }

    public function update(Request $req){
        if($req->hasFile('avatar')){
            $user = User::find($req->id);

            $temp = $req->file('avatar');
            $image = Image::make($temp);

            $name = $user->lname . '_' . $user->fname . '-' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/' . env('UPLOAD_URL'));

            $image->resize(250, 250);
            $image->save($destinationPath . $name);
            $user->avatar = 'uploads/' . env('UPLOAD_URL') . $name;
            $user->save();
        }
        else{
            echo DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token', 'avatar']));
        }

        echo Helper::log(auth()->user()->id, 'updated user', $req->id);
    }

    public function update2(Request $req){
        echo DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token', 'sss', 'philhealth', 'tin', 'pagibig']));

        $user = User::find($req->id);
        if($user->role == "Nurse"){
            $nurse = Nurse::where('user_id', $req->id)->first();
            $nurse->sss = $req->sss;
            $nurse->tin = $req->tin;
            $nurse->philhealth = $req->philhealth;
            $nurse->pagibig = $req->pagibig;
            $nurse->save();
            echo Helper::log(auth()->user()->id, 'updated nurse', $nurse->id);
        }
        elseif($user->role == "Receptionist"){
            $receptionist = Receptionist::where('user_id', $req->id)->first();
            $receptionist->sss = $req->sss;
            $receptionist->tin = $req->tin;
            $receptionist->philhealth = $req->philhealth;
            $receptionist->pagibig = $req->pagibig;
            $receptionist->save();
            echo Helper::log(auth()->user()->id, 'updated receptionist', $receptionist->id);
        }
        elseif($user->role == "Doctor"){
            $doctor = Doctor::where('user_id', $req->id)->first();
            $doctor->sss = $req->sss;
            $doctor->tin = $req->tin;
            $doctor->philhealth = $req->philhealth;
            $doctor->pagibig = $req->pagibig;
            $doctor->save();
            echo Helper::log(auth()->user()->id, 'updated doctor', $doctor->id);
        }

        echo Helper::log(auth()->user()->id, 'updated user', $req->id);
    }

    public function removeType(Request $req){
        echo User::where('id', $req->id)->update(["type" => ""]);
    }

    public function updatePassword(Request $req){
        $user = User::find($req->id);
        $user->password = $req->password;

        Helper::log(auth()->user()->id, 'updated password of user', $req->id);

        $user->save();
    }

    public function profile(){
        $user = Doctor::where('user_id', auth()->user()->id)->first();
        $user->load('user');

        $nurse = Nurse::where('doctor_id', auth()->user()->id)->get();
        $nurse->load('user');

        $settings = Setting::where('clinic', env("CLINIC"))->pluck('value', 'name');

        return $this->_view('profile', [
            'title' => "Profile",
            'data' => $user,
            'nurses' => $nurse,
            'settings' => $settings
        ]);
    }

    public function delete(Request $req){
        $user = User::find($req->id);

        if($user->role == "Nurse"){
            Nurse::where('user_id', $user->id)->delete();
        }
        elseif($user->role == "Receptionist"){
            Receptionist::where('user_id', $user->id)->delete();
        }
        elseif($user->role == "Doctor"){
            Doctor::where('user_id', $user->id)->delete();
        }

        $user->delete();

        Helper::log(auth()->user()->id, 'deleted user', $req->id);
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
