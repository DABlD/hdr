<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Patient, PatientPackage, Package};

class MHR extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = Patient::all();
        $package = Package::find(2);

        foreach($patients as $patient){
            if(PatientPackage::where('patient_id', $patient->id)->where('package_id', 2)->count() == 0){
                $temp = new PatientPackage();
                $temp->user_id = $patient->user_id;
                $temp->patient_id = $patient->id;
                $temp->package_id = $package->id;
                $temp->type = "PEE";

                $temp->details = json_encode($package->toArray());
                $temp->save();
            }
        }
    }
}
