<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('tc', 11)->nullable();
            $table->date('birthday')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->unique();
            $table->boolean('service_contract')->nullable();
            $table->foreignId('gender_id')->nullable()->references('id')->on('genders');
            $table->boolean('optional_contact')->default(false)->nullable();
            $table->boolean('kvkk');
            $table->boolean('black_list')->default(0);
            $table->string('google_st')->nullable();
            $table->string('network')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('parents');
    }
}
