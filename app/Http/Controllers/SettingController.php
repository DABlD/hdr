<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use DB;
use Image;

use App\Helpers\Helper;

class SettingController extends Controller
{
    public function get(Request $req){
        $array = Setting::where('clinic', env('CLINIC'))->select($req->select);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], $req->where[1]);
        }

        // IF HAS WHERENOTNULL
        if($req->whereNotNull){
            $array = $array->whereNotNull($req->whereNotNull);
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

    public function checkClinicSettings(){
        $ctr = Setting::where('clinic', env('CLINIC'))->whereIn('name', ["logo","clinic_name","address","contact_no"])->count();
        if($ctr >= 4){
            echo true;
        }
        else{
            echo false;
        }
    }

    public function update(Request $req){
        if($req->hasFile('logo')){
            $setting = Setting::where('clinic', env('CLINIC'))->where("name", "logo")->first();

            // IF NO SETTING YET
            if(!$setting){
                $setting = new Setting();
                $setting->name = "logo";
                $setting->clinic = env('CLINIC');
            }

            $temp = $req->file('logo');
            $image = Image::make($temp);

            $name = 'Logo-' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/' . env('UPLOAD_URL'));

            $image->resize(250, 250);
            $image->save($destinationPath . $name);
            $setting->value = 'uploads/' . env('UPLOAD_URL') . $name;
            $setting->save();
        }
        else{
            $keys = array_keys($req->except("_token"));
            $setting = Setting::where('clinic', env('CLINIC'))->pluck('value', 'name');

            foreach($keys as $key){
                if(isset($setting[$key])){
                    Setting::where('clinic', env('CLINIC'))->where('name', $key)->update(['value' => $req->$key]);
                }
                else{
                    $setting = new Setting();
                    $setting->clinic = env('CLINIC');
                    $setting->name = $key;
                    $setting->value = $req->$key;
                    $setting->save();
                }
            }

            Helper::log(auth()->user()->id, 'Updated Settings', 0);
        }

    }
}
