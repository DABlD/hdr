<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string('company');
            $table->unsignedInteger('package_id')->nullable();
            $table->unsignedInteger('pax');
            $table->unsignedInteger('completed')->default(0);
            $table->unsignedInteger('pending')->default(0);
            $table->json('pp_ids')->default('[]');

            $table->enum('status', ['Ongoing', 'Completed', 'Cancelled'])->default('Ongoing')->nullable();

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
        Schema::dropIfExists('transactions');
    }
}
