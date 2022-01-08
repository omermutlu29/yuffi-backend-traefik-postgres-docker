<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_children', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->unsignedBigInteger('parent_child_id');
            $table->foreign('parent_child_id')->references('id')->on('parent_children');
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
        Schema::dropIfExists('appointment_children');
    }
}
