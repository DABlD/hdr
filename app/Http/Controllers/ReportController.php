<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use DB;

use App\Models\{User, Setting};
use App\Models\{ExamList, PatientPackage, Transaction};

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReportController extends Controller
{
    public function __construct(){
        $this->table = "reports";
    }

    function exam(Request $req){
        $array = ExamList::select('exam_lists.*', 'p.company_name');

        $array->join('users as u', 'u.id', '=', 'exam_lists.user_id');
        $array->join('patients as p', 'p.user_id', '=', 'u.id');

        // FILTERS
        $filters = $req->filters;
        $array = $array->where(function($q) use($filters){
            if(isset($filters["fFname"])){
                $q->where('fname', 'LIKE', "%" . $filters["fFname"] . "%");
            }
            if(isset($filters["fLname"])){
                $q->where('lname', 'LIKE', "%" . $filters["fLname"] . "%");
            }
        });

        $array = $array->where(function($q) use($filters){
            if($filters["fCompany"] == ""){
                $q->whereNull('company_name');
            }
            else{
                $q->where('company_name', 'LIKE', $filters["fCompany"]);

                if($filters["fCompany"] == "%%"){
                    $q->orWhereNull('company_name');
                }
            }
        });

        $array = $array->where('exam_lists.type', $filters['fType']);
        $array = $array->where('exam_lists.created_at', '>=', $filters['from']);
        $array = $array->where('exam_lists.created_at', '<=', $filters['to']);
        // END FILTERS

        $array = $array->get();
        $array->load('user.patient.exams');

        $filename = "$req->type - " . str_replace('-', '', $filters['from']) . '-' . str_replace('-', '', $filters['to']);

        $class = "App\\Exports\\Exam";

        return Excel::download(new $class($array, $req->type), "$filename.xlsx");
    }

    function packagesSold(Request $req){
        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, now()->parse($req->to)->endOfDay()])
                ->where('package_id', '!=', 1)
                ->where('package_id', '!=', 2)
                ->where('p.company', 'like', $req->company)
                ->select('patient_packages.*', 'p.company')
                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                ->get();

        $data->load('user.patient');
        $data->load('package');

        $company = $req->company == "%%" ? "All Company" : preg_replace('/[^A-Za-z0-9\-]/', '', $req->company);

        $filename = "Packages Sold ($req->from - $req->to) ($company)";
        
        $class = "App\\Exports\\PackageSold";

        return Excel::download(new $class($data), "$filename.xlsx");
    }

    function exportTransactions(Request $req){
        $data = Transaction::whereBetween('created_at', [$req->from, now()->parse($req->to)->endOfDay()])
                ->where('company', 'like', $req->company)
                ->get();

        $data->load('package');

        $company = $req->company == "%%" ? "All Company" : preg_replace('/[^A-Za-z0-9\-]/', '', $req->company);

        $filename = "Transactions ($req->from - $req->to) ($company)";
        
        $class = "App\\Exports\\Transaction";

        return Excel::download(new $class($data), "$filename.xlsx");
    }

    function sales(Request $req){
        $data = PatientPackage::whereBetween('patient_packages.created_at', [$req->from, now()->parse($req->to)->endOfDay()])
                                ->where('package_id', '!=', 1)
                                ->where('package_id', '!=', 2)
                                ->where('p.company', 'like', $req->company)
                                ->select('patient_packages.*', 'p.company')
                                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                                ->get();
        
        $dates = $this->getDates($req->from, $req->to);
        $total = 0;
        $array = [];

        // INIT
        foreach($dates as $date){
            $array[$date] = [];
        }

        // FILL DATA
        foreach($data as $request){
            $details = json_decode($request->details);
            $temp = [
                "company" => $details->company,
                "name" => $details->name,
                "amount" => $details->amount
            ];

            array_push($array[now()->parse($request->created_at)->toDateString()], $temp);
        }

        $company = $req->company == "%%" ? "All Company" : preg_replace('/[^A-Za-z0-9\-]/', '', $req->company);

        $filename = "Sales ($req->from - $req->to) ($company)";
        
        $class = "App\\Exports\\Sales";

        $data = [];
        $sales = $array;
        // $data['dates'] = $dates;
        // $data['total'] = $total;

        return Excel::download(new $class($sales), "$filename.xlsx");
    }

    function analytics(Request $req){
        $companies = User::select('id', 'fname')->where('role', 'Company')->distinct()->get();

        return view("analytics.index", [
            "title" => "Analytics",
            "companies" => $companies
        ]);
    }

    function getReport1(Request $req){
        $f = $req->filters;
        $from = $f['from'] . ' 00:00:00';
        $to = $f['to'] . ' 23:59:59';

        $pps = PatientPackage::whereBetween('patient_packages.updated_at', [$from, $to])
                ->join('users as u', 'u.id', '=', 'patient_packages.user_id')
                ->join('packages as p', 'p.id', '=', 'patient_packages.package_id')
                ->where('p.company', 'like', $f['company'])
                ->where('patient_packages.type', 'like', $f['type'])
                ->select('patient_packages.*', 'u.fname', 'u.lname', 'u.gender', 'u.birthday', 'p.company');

        if(str_contains($f['name'], ",")){
            $temp = explode(",", $f['name']);

            $lname = $temp[0];

            $fname = explode(" ", trim($temp[1]));
            
            if(sizeof($fname) > 1){
                $fname = array_slice($fname, 0, -1);
            }

            $fname = implode(" ", $fname);

            $pps = $pps->where(function($q) use($fname, $lname){
                $q->where('u.fname', 'like', "%" . $fname . "%");
                $q->where('u.lname', 'like', "%" . $lname . "%");
            });
        }
        else{
            $pps = $pps->where(function($q) use($f){
                $q->where('u.fname', 'like', "%" . $f['name'] . "%");
                $q->orWhere('u.lname', 'like', "%" . $f['name'] . "%");
            });
        }

        if(isset($f['classification'])){
            $pps = $pps->where('classification', $f['classification'])
                        ->select(
                            'patient_packages.id', 
                            'patient_packages.user_id',
                            'patient_packages.classification',
                            'patient_packages.clinical_assessment',
                            'patient_packages.c_remarks',
                            'patient_packages.recommendation');

            if($f['classification'] == "Pending"){
                $pps = $pps->orWhere('classification', null);
            }
        }

        $pps = $pps->get();
        $array = [];

        foreach($pps as $pp){
            $user = $pp->user;

            if(str_contains(strtolower($user->fullname), strtolower($f['name'])) || str_contains(strtolower($user->namefull), strtolower($f['name']))){
                array_push($array, $pp);
            }
        }

        echo json_encode($array);
    }

    function sendEmailReminder(Request $req){
        $pp = PatientPackage::find($req->id);

        $temp = Setting::pluck('value', 'name');

        require base_path("vendor/autoload.php");

        $mail = new PHPMailer(true);     // Passing `true` enables exceptions
        try {
            // Email server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');   //  sender username
            $mail->Password = env('MAIL_PASSWORD');       // sender password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // encryption - ssl/tls
            $mail->Port = 587;                          // port - 587/465

            $mail->setFrom('info@onehealthnetwork.com.ph', 'One Health Network');
            // $mail->addAddress($pp->user->email);
            $mail->addAddress("darm.111220@gmail.com");

            $mail->isHTML(true);                // Set email content format to HTML

            $mail->Subject = "OHN Reminder";

            $mail->Body    = view('analytics.reminder', ['pp' => $pp, 'settings' => Setting::pluck('value', 'name')])->render();

            if( !$mail->send() ) {
                echo "
                    <script>
                        window.alert('Email sending error.');
                        window.close();
                    </script>
                ";
            }
            
            else {
                echo "
                    <script>
                        window.alert('Email sent successfully. Please check your email');
                        window.close();
                    </script>
                ";
            }

        } catch (Exception $e) {
            dd($e->errorMessage());
            echo "Error. Email not sent";
        }
    }

    function sendEmailToAll(Request $req){
        $temp = Setting::pluck('value', 'name');

        require base_path("vendor/autoload.php");

        foreach ($req->ids as $id) {
            $user = User::find($id);

            $mail = new PHPMailer(true);     // Passing `true` enables exceptions
            try {
                // Email server settings
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';             //  smtp host
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');   //  sender username
                $mail->Password = env('MAIL_PASSWORD');       // sender password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // encryption - ssl/tls
                $mail->Port = 587;                          // port - 587/465

                $mail->setFrom('info@onehealthnetwork.com.ph', 'One Health Network');
                // $mail->addAddress($user->email);
                $mail->addAddress("darm.111220@gmail.com");

                $mail->isHTML(true);                // Set email content format to HTML

                $mail->Subject = "OHN Reminder";

                $mail->Body    = view('analytics.reminder', ['user' => $user, 'settings' => $temp])->render();
                $mail->send();
            } catch (Exception $e) {
                dd($e->errorMessage());
                echo "Error. Email not sent";
            }

        }

        echo "Success";
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

    public function index(){
        $companies = User::where('role', 'Company')->distinct()->pluck('fname');

        return $this->_view('index', [
            'title' => "Reports",
            'companies' => $companies
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table.$view", $data);
    }
}
