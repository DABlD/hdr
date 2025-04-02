<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Package, PatientPackage, Question};

use App\Helpers\Helper;

class SubjectiveController extends Controller
{
    public function index(Request $req){
        $user = User::find($req->id);
        $user->load('patient');

        return $this->_view('subjective', [
            'title' => "Fill-up Info",
            'user' => $user
        ]);
    }

    public function search(Request $req){
        return $this->_view('search', [
            'title' => "Search Patient"
        ]);
    }

    public function getPatientPackage(Request $req){
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

    public function getQuestion(Request $req){
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

    public function updateSubjective(Request $req){
        $result = PatientPackage::where('id', $req->id)->first();
        // PatientPackage::where('id', $req->id)->update($req->except(['id', '_token']));

        $pid = $result->patient_id;
        $pendings = PatientPackage::where('patient_id', $pid)->where('package_id', '>', 2)->where('status', 'Pending')->get();

        foreach($pendings as $pending){
            $qwas1 = $pending->question_with_answers;

            if($qwas1 == null){
                $pending->question_with_answers = $req->question_with_answers;
                $pending->save();
            }
            else{
                $qwas1 = json_decode($qwas1);
                $qwas2 = json_decode($req->question_with_answers);
                $temp = [];

                foreach($qwas2 as $qwa2){
                    $c1 = ($qwa2->id >= 114 && $qwa2->id <= 128);
                    $c2 = ($qwa2->id >= 135 && $qwa2->id <= 143);
                    $c3 = in_array($qwa2->id, [145, 146, 148, 284, 271, 272, 278, 279]);

                    if($c1 || $c2 || $c3){
                        foreach($qwas1 as $qwa1){
                            // $c4 = ($qwa1->id >= 114 && $qwa1->id <= 128);
                            // $c5 = ($qwa1->id >= 135 && $qwa1->id <= 143);
                            // $c6 = in_array($qwa1->id, [145, 146, 148, 284, 271, 272, 278, 279]);

                            if($qwa1->id == $qwa2->id){
                                $qwa1->answer = $qwa2->answer;
                                $qwa1->remark = $qwa2->remark;
                            }

                        }
                    }
                }

                $pending->question_with_answers = json_encode($qwas1);
                $pending->save();
            }
        }

        $result->question_with_answers = $req->question_with_answers;
        $result->updated_at = now();
        $result->save();

        echo Helper::log($result->user_id, 'subjective was answered', null);
    }

    private function _view($view, $data = array()){
        return view("patients.$view", $data);
    }
}
