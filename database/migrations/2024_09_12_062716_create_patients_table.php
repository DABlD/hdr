<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger("user_id")->nullable();

            $table->string('patient_id')->nullable();
            $table->string('hmo_provider')->nullable();
            $table->string('hmo_number')->nullable();

            $table->string('mothers_name')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('guardian_name')->nullable();

            $table->string('employment_status')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_position')->nullable();
            $table->string('company_contact')->nullable();
            $table->string('sss')->nullable();
            $table->string('tin_number')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}