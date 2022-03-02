<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterSharedTalentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_shared_talents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->foreignId('shareable_talent_id')->references('id')->on('shareable_talents');
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
        Schema::dropIfExists('baby_sitter_shared_talents');
    }
}
