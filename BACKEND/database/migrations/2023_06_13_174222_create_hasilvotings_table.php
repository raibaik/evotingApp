<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilVotingsTable extends Migration
{
    public function up()
    {
        Schema::create('hasilvotings', function (Blueprint $table) {
            $table->id('IdHasilVoting');
            $table->unsignedBigInteger('id_calon');
            $table->integer('total_suara');

            $table->foreign('id_calon')->references('id_calon')->on('calons')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasilvotings');
    }
}