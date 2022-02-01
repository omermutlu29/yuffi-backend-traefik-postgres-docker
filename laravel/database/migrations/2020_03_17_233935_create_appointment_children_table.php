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
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('gender_id')->constrained();
            $table->foreignId('child_year_id')->constrained();
            $table->boolean('disable')->default(false);
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
