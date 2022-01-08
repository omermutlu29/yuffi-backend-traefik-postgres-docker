<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_penalties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('baby_sitter_id')->unsigned();
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->bigInteger('penalty_type_id')->unsigned();
            $table->foreign('penalty_type_id')->references('id')->on('penalty_types');
            $table->decimal('updated_deposit');
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
        Schema::dropIfExists('baby_sitter_penalties');
    }
}
