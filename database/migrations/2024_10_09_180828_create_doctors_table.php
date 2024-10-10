<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger("user_id");

            $table->string('sss')->nullable();
            $table->string('tin')->nullable();
            $table->string('philhealth')->nullable();

            $table->string('license_number')->nullable();
            $table->string('s2_number')->nullable();
            $table->string('ptr')->nullable();
            $table->string('specialization')->nullable();
            $table->string('pharma_partner')->nullable();
            $table->json('title')->nullable();

            $table->json('medical_association')->nullable();
            $table->json('diplomate')->nullable();

            $table->string('signature')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
