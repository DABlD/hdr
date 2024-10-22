<?php

namespace App\Imports;
use Illuminate\Http\Request;

use App\Models\{User, Patient, Package, PatientPackage};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow};

class EmployeeImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($company){
        $this->company     = $company;
    }

    public function collection(Collection $data)
    {   
        $size = sizeof($data);

        $ctr = Patient::where('created_at', 'like', now()->format('Y-m-d') . '%')->count();

        for($i = 1; $i < $size; $i++)
        {
            if($data[$i][0] != "" && $data[$i][1] != ""){
                $user = new User();
                $user->role = "Patient";
                $user->fname = $data[$i][0];
                $user->mname = $data[$i][1];
                $user->lname = $data[$i][2];
                $user->prefix = $data[$i][3];
                $user->suffix = $data[$i][4];
                $user->gender = $data[$i][5];
                $user->birthday = $data[$i][6] != "" ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[$i][6]) : "";
                $user->civil_status = $data[$i][7];
                $user->birth_place = $data[$i][8];
                $user->address = $data[$i][9];
                $user->email = $data[$i][10];
                $user->contact = $data[$i][11];
                $user->nationality = $data[$i][12];
                $user->religion = $data[$i][13];
                $user->password = "12345678";
                $user->username = $data[$i][10];
                $user->save();

                $patient = new Patient();
                $patient->user_id = $user->id;
                $patient->patient_id = "P" . now()->format('ymd') . str_pad($ctr+1, 5, '0', STR_PAD_LEFT);
                $patient->hmo_provider = $data[$i][14];
                $patient->hmo_number = $data[$i][15];
                $patient->mothers_name = $data[$i][16];
                $patient->fathers_name = $data[$i][17];
                $patient->guardian_name = $data[$i][18];
                $patient->employment_status = $data[$i][19];
                $patient->company_name = $this->company;
                $patient->company_position = $data[$i][20];
                $patient->company_contact = $data[$i][21];
                $patient->sss = $data[$i][22];
                $patient->tin_number = $data[$i][22];
                $patient->save();

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

                $ctr++;
            }
        }
    }
}