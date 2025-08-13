<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_packages', function (Blueprint $table) {
            $table->id();

            $table->string('type'); //APE OR PEE OR ECU

            $table->unsignedInteger("user_id");
            $table->unsignedBigInteger("patient_id");

            $table->unsignedInteger("package_id");
            $table->json('details');
            $table->json('question_with_answers')->nullable();
            $table->text('remarks')->nullable();
            $table->string('file')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('patient_id')
                  ->references('id')
                  ->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_packages');
    }
}
