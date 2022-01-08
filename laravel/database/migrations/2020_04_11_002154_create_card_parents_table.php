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
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('parents');
            $table->string('cardtoken');
            $table->string('carduserkey');
            $table->string('cardfamily');
            $table->string('cardalias');
            $table->string('lastfourdigits');
            $table->string('cardtype');
            $table->string('cardassociation');
            $table->string('cardbankname');
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
