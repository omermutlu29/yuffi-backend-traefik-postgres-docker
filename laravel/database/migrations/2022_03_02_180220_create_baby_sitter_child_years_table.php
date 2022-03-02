<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterChildYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_child_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->foreignId('child_year_id')->references('id')->on('child_years');
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
        Schema::dropIfExists('baby_sitter_child_years');
    }
}
