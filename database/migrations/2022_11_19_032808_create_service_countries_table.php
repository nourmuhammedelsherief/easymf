<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_countries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->unique();
            $table->foreignId('service_id');
            $table->float('price')->unsigned();
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_countries');
    }
}
