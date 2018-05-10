<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paths', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lane_id')->unsigned();
            $table->integer('station1_id')->unsigned();
            $table->integer('station2_id')->unsigned();
            $table->integer('time');
            $table->timestamps();
        });

        Schema::table('paths', function (Blueprint $table) {
            $table->foreign('lane_id')
                    ->references('id')->on('lanes')
                    ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('station1_id')
                    ->references('id')->on('stations')
                    ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('station2_id')
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
        Schema::dropIfExists('paths');
    }
}
