<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, PatientPackage};

class DashboardController extends Controller
{
    function index(){
        $users = User::where('role', 'Patient')->count();
        $companies = User::where('role', 'Company')->distinct()->get();

        $pps = PatientPackage::all();

        $ppcount = $pps->count();
        $total = 0;

        foreach($pps as $pp){
            $temp = json_decode($pp->details);
            $total += $temp->amount;
        }

        return $this->_view('dashboard', [
            'title'     => 'Dashboard',
            'users'     => $users,
            'companies' => $companies,
            'ppcount'   => $ppcount,
            'total'     => $total
        ]);
    }

    function getReport1(Request $req){
        $dates = $this->getDates($req->from, $req->to);

        $temp = [];
        $temp2 = []; //APE
        $temp3 = []; //PEE
        $temp4 = []; //ECU
        foreach($dates as $date){
            $date = now()->parse($date)->toDateString();
            $temp[$date] = 0;
            $temp2[$date] = 0;
            $temp3[$date] = 0;
            $temp4[$date] = 0;
        }

        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, now()->parse($req->to)->endOfDay()])
                                ->where('package_id', '!=', 1)
                                ->where('package_id', '!=', 2)
                                ->where('p.company', 'like', $req->company)
                                ->select('patient_packages.*', 'p.company')
                                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                                ->get();

        foreach($data as $request){
            $temp[now()->parse($request->created_at)->toDateString()]++;

            if($request->type == "APE"){
                $temp2[now()->parse($request->created_at)->toDateString()]++;
            }
            elseif($request->type == "PEE"){
                $temp3[now()->parse($request->created_at)->toDateString()]++;
            }
            else{
                $temp4[now()->parse($request->created_at)->toDateString()]++;
            }
        }

        $labels = [];
        foreach($dates as $date){
            array_push($labels, now()->parse($date)->format('M d'));
        }

        // $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        // $color2 = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        // $color3 = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

        $color = "#36a2eb";
        $color2 = "#fe6383";
        $color3 = "#4ac0c0";
        $color4 = "#e9f5f5";

        $dataset = [
            [
                'label' => "Total",
                'data' => array_values($temp),
                'borderColor' => $color,
                'backgroundColor' => $color,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "APE",
                'data' => array_values($temp2),
                'borderColor' => $color2,
                'backgroundColor' => $color2,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "PPE",
                'data' => array_values($temp3),
                'borderColor' => $color3,
                'backgroundColor' => $color3,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "ECU",
                'data' => array_values($temp4),
                'borderColor' => $color4,
                'backgroundColor' => $color4,
                'hoverRadius' => 10,
                'tension' => 0.1
            ]
        ];

        $data->load('package');

        echo json_encode(["table" => $data, "chart" => ['labels' => $labels, 'dataset' => $dataset]]);
    }

    function getReport2(Request $req){
        $dates = $this->getDates($req->from, $req->to);

        $temp = [];
        $temp2 = []; //APE
        $temp3 = []; //PEE
        $temp4 = []; //ECU
        foreach($dates as $date){
            $date = now()->parse($date)->toDateString();
            $temp[$date] = 0;
            $temp2[$date] = 0;
            $temp3[$date] = 0;
            $temp4[$date] = 0;
        }

        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, now()->parse($req->to)->endOfDay()])
                                ->where('package_id', '!=', 1)
                                ->where('package_id', '!=', 2)
                                ->where('p.company', 'like', $req->company)
                                ->select('patient_packages.*', 'p.company')
                                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                                ->get();

        foreach($data as $request){
            $details = json_decode($request->details);
            $amount = $details->amount;

            // $temp[now()->parse($request->created_at)->toDateString()] += $amount;
            $temp[now()->parse($request->created_at)->toDateString()] += 1;

            if($request->type == "APE"){
                // $temp2[now()->parse($request->created_at)->toDateString()] += $amount;
                $temp2[now()->parse($request->created_at)->toDateString()] += 1;
            }
            elseif($request->type == "PEE"){
                $temp3[now()->parse($request->created_at)->toDateString()]++;
            }
            else{
                $temp4[now()->parse($request->created_at)->toDateString()]++;
            }
        }

        $labels = [];
        foreach($dates as $date){
            array_push($labels, now()->parse($date)->format('M d'));
        }

        // $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        // $color2 = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        // $color3 = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

        $color = "#ff9f40";
        $color2 = "#9966ff";
        $color3 = "#ffcc55";
        $color4 = "#e9f5f5";

        $dataset = [
            [
                'label' => "Total",
                'data' => array_values($temp),
                'borderColor' => $color,
                'backgroundColor' => $color,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "APE",
                'data' => array_values($temp2),
                'borderColor' => $color2,
                'backgroundColor' => $color2,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "PPE",
                'data' => array_values($temp3),
                'borderColor' => $color3,
                'backgroundColor' => $color3,
                'hoverRadius' => 10,
                'tension' => 0.1
            ],
            [
                'label' => "ECU",
                'data' => array_values($temp4),
                'borderColor' => $color4,
                'backgroundColor' => $color4,
                'hoverRadius' => 10,
                'tension' => 0.1
            ]
        ];

        $data->load('package');

        echo json_encode(["table" => $data, "chart" => ['labels' => $labels, 'dataset' => $dataset]]);
    }

    private function getDates($from, $to){
            $dates = [];

            while($from <= $to){
                $tempDate = now()->parse($from);
                array_push($dates, $tempDate->toDateString());
                $from = $tempDate->addDay()->toDateString();
            }

            return $dates;
        }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
