<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lane_id')->unsigned();
            $table->integer('last_id')->unsigned();
            $table->integer('next_id')->unsigned();
            $table->integer('direction');
            $table->timestamps();
        });

        Schema::table('trains', function (Blueprint $table) {
            $table->foreign('lane_id')
                    ->references('id')->on('lanes')
                    ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('last_id')
                    ->references('id')->on('stations')
                    ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('next_id')
                    ->references('id')->on('stations')
                    ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trains');
    }
}
