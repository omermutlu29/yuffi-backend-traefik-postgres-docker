<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_regions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('baby_sitter_id')->unsigned();
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->bigInteger('town_id')->unsigned();
            $table->foreign('town_id')->references('id')->on('towns');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baby_sitter_regions');
    }
}
