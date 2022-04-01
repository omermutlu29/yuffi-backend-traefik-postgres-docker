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
            $table->foreignId('available_date_id')->references('id')->on('baby_sitter_available_dates');
            $table->foreignId('time_status_id')->references('id')->on('time_statuses');
            $table->time('start');
            $table->time('finish');
            $table->boolean('is_active')->default(0);
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
