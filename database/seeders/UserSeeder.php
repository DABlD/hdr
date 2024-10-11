<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Patient, Doctor, Nurse};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'sadmin',
            'fname' => 'Super',
            'mname' => 'Duper',
            'lname' => 'Admin',
            'role' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => 'Earth',
            'contact' => null,
            'password' => '654321'
        ]);

        // ADMIN

        User::create([
            'username' => 'admin',
            'fname' => 'David',
            'mname' => 'Roga',
            'lname' => 'Mendoza',
            'role' => 'Admin',
            'email' => 'davidmendozaofficial@gmail.com',
            'birthday' => "1997-11-12",
            'gender' => 'Male',
            'address' => "M. Adriatico St, Malate, Manila, 1004 Metro Manila",
            'contact' => "09154590172",
            'password' => '123456'
        ]);

        Doctor::create(['user_id' => 2]);

        // User::create([
        //     'username' => 'chanragmat',
        //     'fname' => 'Christian',
        //     'mname' => 'Lagunsad',
        //     'lname' => 'Ragmat',
        //     'role' => 'Admin',
        //     'email' => 'chan.ragmat@gmail.com',
        //     'birthday' => null,
        //     'gender' => 'Male',
        //     'address' => null,
        //     'contact' => null,
        //     'password' => '123456'
        // ]);

        // Nurse
        User::create([
            'username' => 'nurse',
            'fname' => 'Nurse',
            'mname' => 'D',
            'lname' => 'Doe',
            'role' => 'Nurse',
            'email' => 'nurse@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => null,
            'contact' => null,
            'password' => '123456'
        ]);

        Nurse::create(['user_id' => 3, 'doctor_id' => 2]);

        // RECEPTIONIST
        User::create([
            'username' => 'receptionist',
            'fname' => 'Receptionist',
            'mname' => 'D',
            'lname' => 'Doe',
            'role' => 'Receptionist',
            'email' => 'receptionist@gmail.com',
            'birthday' => null,
            'gender' => 'Female',
            'address' => null,
            'contact' => null,
            'password' => '123456'
        ]);

        // PATIENT

        User::create([
            'username' => 'patient',
            'fname' => 'John',
            'mname' => 'D',
            'lname' => 'Doe',
            'role' => 'Patient',
            'email' => 'patient@gmail.com',
            'birthday' => "1997-11-12",
            'gender' => 'Male',
            'address' => "Philippines",
            'contact' => "091239123",
            'password' => '123456',
            'civil_status' => "Married",
            'birth_place' => "Philippines",
            'nationality' => "Filipino",
            'religion' => "Catholic"
        ]);

        Patient::create([
            'user_id' => 5,
            'patient_id' => 'P24091600001',
            'hmo_provider' => 'maxicare',
            'hmo_number' => '1234567809121482',
            'mothers_name' => "Mary Doe",
            'fathers_name' => "Jason Doe",
            'guardian_name' => "Test",
            'employment_status' => "Hired - Private",
            'company_name' => "SOLPIA",
            'company_position' => "Software Developer",
            'company_contact' => "81234-123123",
            'sss' => "Test",
            'tin_number' => "Test"
        ]);
    }
}