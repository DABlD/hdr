<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Patient, PatientPackage, Package, User};

class PatientPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = Patient::all();

        $types = ["APE", "PEE"];

        foreach($patients as $patient){
            $packages = Package::where('company', $patient->company_name)->get()->toArray();

            if(rand(0, 1)){
                $package = $packages[rand(0,sizeof($packages) - 1)];

                $temp = new PatientPackage();
                $temp->type = $types[rand(0, 1)];
                $temp->user_id = $patient->user_id;
                $temp->patient_id = $patient->id;
                $temp->package_id = $package['id'];
                $temp->details = json_encode($package);
                $temp->created_at = now()->subDays(rand(0, 7))->toDateTimeString();
                $temp->save();

                $user = User::find($patient->user_id);
                $user->type = $temp->type;
                $user->save();
            }
        }
    }
}
