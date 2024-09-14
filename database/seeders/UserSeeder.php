<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Patient};

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
            'birthday' => null,
            'gender' => 'Male',
            'address' => null,
            'contact' => null,
            'password' => '123456'
        ]);

        User::create([
            'username' => 'chanragmat',
            'fname' => 'Christian',
            'mname' => 'Lagunsad',
            'lname' => 'Ragmat',
            'role' => 'Admin',
            'email' => 'chan.ragmat@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => null,
            'contact' => null,
            'password' => '123456'
        ]);

        // DOCTOR

        User::create([
            'username' => 'doctor',
            'fname' => 'John',
            'mname' => 'D',
            'lname' => 'Doe',
            'role' => 'Doctor',
            'email' => 'doctor@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => null,
            'contact' => null,
            'password' => '123456'
        ]);

        // RECEPTIONIST

        User::create([
            'username' => 'receptionist',
            'fname' => 'Jane',
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
            'fname' => 'Mark',
            'mname' => 'E',
            'lname' => 'Escario',
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
            'user_id' => 6,
            'patient_id' => '000001',
            'hmo_provider' => 'maxicare',
            'hmo_number' => '1234567809121482'
        ]);
    }
}