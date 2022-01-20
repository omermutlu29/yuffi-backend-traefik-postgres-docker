<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterAvailableTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_available_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('available_date_id')->unsigned();
            $table->foreign('available_date_id')->references('id')->on('baby_sitter_available_dates');
            $table->time('start');
            $table->time('finish');
            $table->boolean('is_active')->default(0);
            $table->unsignedBigInteger('time_status_id');
            $table->foreign('time_status_id')->references('id')->on('time_statuses');
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
        Schema::dropIfExists('baby_sitter_available_times');
    }
}
