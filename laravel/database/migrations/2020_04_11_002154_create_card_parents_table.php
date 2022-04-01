<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_parents', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('parent_id')->references('id')->on('parents');
            $table->string('cardtoken');
            $table->string('carduserkey');
            $table->string('cardalias');
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
        Schema::dropIfExists('card_parents');
    }
}
