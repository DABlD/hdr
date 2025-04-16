<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesInExamLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_lists', function (Blueprint $table) {
            $table->text('queued_dates')->after('queued_at')->nullable()->default('[]')->change();
            $table->text('queued_doctors')->after('doctor_id')->nullable()->default('[]')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_lists', function (Blueprint $table) {
            //
        });
    }
}
