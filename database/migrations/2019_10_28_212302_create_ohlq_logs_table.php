<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOhlqLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ohlq_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bourbon_id', 10);
            $table->json('data');
            $table->timestamps();

            $table->foreign('bourbon_id')->references('id')->on('bourbons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ohlq_logs');
    }
}
