<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->string('phone');
            $table->string('user_type');
            $table->text('message');
            $table->boolean('send_status')->default(0);
            $table->boolean('saw')->default(0);
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
        Schema::dropIfExists('appointment_messages');
    }
}
