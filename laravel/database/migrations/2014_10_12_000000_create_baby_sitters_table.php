<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBabySittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baby_sitters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('tc', 11)->nullable();
            $table->timestamp('birthday')->nullable();
            $table->string('photo')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('criminal_record')->nullable();
            $table->text('about')->nullable();
            $table->boolean('service_contrct')->nullable();
            $table->unsignedBigInteger('gender_id')->default(3);
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->unsignedBigInteger('baby_sitter_status_id')->default(1);
            $table->foreign('baby_sitter_status_id')->references('id')->on('baby_sitter_statuses');
            $table->boolean('kvkk')->nullable();
            $table->boolean('is_accepted')->default(0);
            $table->boolean('black_list')->default(0);
            $table->string('google_st')->nullable();
            $table->ipAddress('network')->nullable();
            $table->decimal('deposit')->default(0);
            $table->decimal('point')->default(0);
            //That's about preferences start
            $table->decimal('price_per_hour')->nullable();
            $table->unsignedBigInteger('child_gender_id')->default(3);
            $table->foreign('child_gender_id')->references('id')->on('genders');
            $table->unsignedBigInteger('child_year_id')->default(3);
            $table->foreign('child_year_id')->references('id')->on('child_years');
            $table->integer('child_count')->default(0);
            $table->boolean('disabled_status')->default(0);
            $table->boolean('animal_status')->default(0);
            //That's about preferences end
            $table->string('sub_merchant')->nullable();
            $table->string('iban')->nullable();
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
        Schema::dropIfExists('baby_sitter');
    }
}
