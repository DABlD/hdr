<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddAdditionalInPatientPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patient_packages', function (Blueprint $table) {
            $table->text('clinical_assessment')->nullable()->after('remarks');
            $table->text('recommendation')->nullable()->after('clinical_assessment');
            $table->string('classification')->nullable()->after('recommendation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_packages', function (Blueprint $table) {
            $table->dropColumn('clinical_assessment');
            $table->dropColumn('recommendation');
            $table->dropColumn('classification');
        });
    }
}
