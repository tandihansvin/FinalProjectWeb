<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusChangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_change_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('time');
            $table->unsignedInteger('header_id');
            $table->unsignedInteger('status_id');
            $table->string('desc');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('header_id')->references('id')->on('transaction_headers');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_change_histories');
    }
}
