<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('created_at')->nullable();
        });

        Schema::create('urls_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('url_id');
            $table->foreign('url_id')->references('id')->on('urls');
            $table->string('status_code');
            $table->string('h1');
            $table->string('keywords');
            $table->string('description');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
