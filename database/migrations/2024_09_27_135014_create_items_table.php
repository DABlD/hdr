<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('name')->nullable();
            $table->string('packaging')->nullable();
            $table->double('amount', 8, 2)->nullable();
            $table->unsignedInteger('reorder')->nullable();
            $table->unsignedInteger('stock')->default(0);

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
        Schema::dropIfExists('items');
    }
}
