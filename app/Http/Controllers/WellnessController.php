<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Wellness, User};
use Image;
use DB;

use App\Helpers\Helper;

class WellnessController extends Controller
{
    public function __construct(){
        $this->table = "wellness";
    }

    public function get(Request $req){
        $array = Wellness::select($req->select);

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
        $wellness = new Wellness();
        $files = [];

        // Handle file upload (if any)
        if ($req->hasFile('files')) {
            foreach ($req->file('files') as $temp) {
                // If it's an image -> process with Intervention
                if (str_starts_with($temp->getMimeType(), 'image/')) {
                    $image = Image::make($temp);

                    $name = 'wellness_' . uniqid() . '.' . $temp->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/' . env('UPLOAD_URL') . 'wellness/' . $req->company . '/');

                    // ensure dir exists
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->save($destinationPath . $name);

                    array_push($files, 'uploads/' . env('UPLOAD_URL') . 'wellness/' . $req->company . '/' . $name);

                } else {
                    // Non-image file (PDF/DOCX) -> just move
                    $name = 'wellness_' . uniqid() . '.' . $temp->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/' . env('UPLOAD_URL') . '/wellness/' . $req->company . '/');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    $temp->move($destinationPath, $name);

                    array_push($files, 'uploads/' . env('UPLOAD_URL') . 'wellness/' . $req->company . '/' . $name);
                }
            }
        }

        // Other fields
        $wellness->files = json_encode($files);
        $wellness->company = $req->company;
        $wellness->recommendation = $req->recommendation;

        $wellness->save();

        // Example log if you have helper
        Helper::log(auth()->user()->id, "created wellness record for $req->company", $wellness->id);

        return response()->json(['status' => 'success']);
    }

    public function index(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('index', [
            'title' => "Wellness Programs and Recommendation",
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
