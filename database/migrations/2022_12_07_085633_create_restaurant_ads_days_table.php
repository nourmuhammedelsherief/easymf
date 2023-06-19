<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantAdsDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_ads_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ads_id');
            $table->foreignId('day_id');
            $table->timestamps();

            $table->foreign('ads_id')->references('id')->on('restaurant_ads')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign('day_id')->references('id')->on('days')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_ads_days');
    }
}
