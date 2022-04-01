<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_location_id')->references('id')->on('appointment_locations');
            $table->foreignId('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->foreignId('parent_id')->references('id')->on('parents');
            $table->foreignId('appointment_status_id')->references('id')->on('appointment_statuses');
            $table->foreignId('town_id')->references('id')->on('towns');
            $table->integer('hour');
            $table->time('start');
            $table->time('finish');
            $table->date('date');
            $table->decimal('price');
            $table->string('location')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
