<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolesInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);

            DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM(
                'Super Admin',
                'Admin',
                'Doctor',
                'Nurse',
                'Patient',
                'Receptionist',
                'Company',
                'Laboratory',
                'Imaging'
            ) NULL");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
