<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterAvaliableDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_avaliable_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('baby_sitter_id');
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->date('date');
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
        Schema::dropIfExists('baby_sitter_avaliable_dates');
    }
}
