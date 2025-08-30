<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PatientPackage, ExamList};

class TestController extends Controller
{
    public function moveUploads(Request $req){
        $pps = PatientPackage::all();

        foreach($pps as $pp){
            $files = $pp->file;

            if($files){
                $files = json_decode($files);
                $temp = [];
                
                foreach($files as $file){
                    $file = str_replace('uploads/', 'uploads/' . env('UPLOAD_URL'), $file);
                    array_push($temp, $file);
                }

                $pp->file = json_encode($temp);
                $pp->save();
            }
        }
    }

    public function copyMHRtoCompletePackage(Request $req){
        $pps = PatientPackage::where('package_id', 2)->get();

        foreach($pps as $pp){
            PatientPackage::where('user_id', $pp->user_id)->where('package_id', '!=', 2)->update(["question_with_answers" => $pp->question_with_answers]);
        }

        echo "done";
    }

    public function defaultjsonvalues(){
        $els = ExamList::all();

        foreach($els as $el){
            if($el->queued_doctors == null){
                $el->queued_doctors = "[]";
            }
            if($el->queued_dates == null){
                $el->queued_dates = "[]";
            }

            $el->save();
        }
    }

    public function capitalizeCompanyInPP(){
        $pps = PatientPackage::all();


        foreach($pps as $pp){
            $details = json_decode($pp->details);

            if($details->company){
                $details->company = strtoupper($details->company);
            }

            if($details->name){
                $details->name = strtoupper($details->name);
            }

            $pp->details = json_encode($details);
            $pp->save();
        }
    }
}
