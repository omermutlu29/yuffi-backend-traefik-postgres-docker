<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_points', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('point_type_id')->references('id')->on('point_types');
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->foreignId('appointment_id')->references('id')->on('appointments');
            $table->integer('point');
            $table->text('additional_text');
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
        Schema::dropIfExists('baby_sitter_points');
    }
}
