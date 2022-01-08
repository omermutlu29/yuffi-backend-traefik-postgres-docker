<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('price');
            $table->unsignedBigInteger('baby_sitter_id');
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->boolean('status')->nullable();
            $table->text('raw_result')->nullable();
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
        Schema::dropIfExists('baby_sitter_deposits');
    }
}
