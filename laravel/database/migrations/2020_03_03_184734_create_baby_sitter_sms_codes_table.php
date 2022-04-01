<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterSmsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_sms_codes', function (Blueprint $table) {
            $table->id('id');
            $table->string('code');
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
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
        Schema::dropIfExists('baby_sitter_sms_codes');
    }
}
