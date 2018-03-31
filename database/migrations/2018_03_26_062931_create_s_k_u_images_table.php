<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSKUImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SKUImages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('SKU_id');
            $table->string('small');
            $table->string('medium');
            $table->string('large');
            $table->timestamps();
            $table->foreign('SKU_id')->references('id')->on('SKU');
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
        Schema::dropIfExists('SKUImages');
    }
}
