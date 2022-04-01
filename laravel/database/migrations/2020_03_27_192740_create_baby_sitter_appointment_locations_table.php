<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySitterAppointmentLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitter_appointment_locations', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->foreignId('location_id')->references('id')->on('appointment_locations');
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
        Schema::dropIfExists('baby_sitter_appointment_locations');
    }
}
