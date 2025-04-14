<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterExamLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_lists', function (Blueprint $table) {
            $table->date('queued_at')->after('doctor_id')->nullable();
            $table->text('queued_dates')->after('queued_at');//->default('[]');
            $table->text('queued_doctors')->after('doctor_id');//->default('[]');
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
            $table->dropColumn('queued_at');
            $table->dropColumn('queued_dates');
            $table->dropColumn('queued_doctors');
        });
    }
}
