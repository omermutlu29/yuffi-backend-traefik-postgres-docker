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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('baby_sitter_id');
            $table->foreign('baby_sitter_id')->references('id')->on('baby_sitters');
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('parents');
            $table->unsignedBigInteger('appointment_status_id');
            $table->foreign('appointment_status_id')->references('id')->on('appointment_statuses');
            $table->integer('hour');
            $table->time('start');
            $table->time('finish');
            $table->date('date');
            $table->decimal('price');
            $table->unsignedBigInteger('appointment_location_id');
            $table->foreign('appointment_location_id')->references('id')->on('appointment_locations');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('town_id');
            $table->foreign('town_id')->references('id')->on('towns');
            $table->decimal('point')->nullable();
            $table->boolean('baby_sitter_approved')->nullable();
            $table->json('payment_raw_result')->nullable();
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
