<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('kyc_id')->nullable();
            $table->string('username')->unique();
            $table->enum('role', ['Super Admin', 'Admin', 'Doctor', 'Nurse', 'Patient', 'Receptionist', 'Company'])->nullable();
            
            $table->string('prefix')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('suffix')->nullable();

            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();

            $table->string('civil_status')->nullable();
            $table->string('birth_place')->nullable();
            
            $table->text('address')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('contact')->nullable();

            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            // FOR IDENTIFYING IF USER IS FOR APE OR PEE
            $table->string('type')->default("");

            $table->string('avatar')->nullable()->default('images/default_avatar.png');
            $table->string('password');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
