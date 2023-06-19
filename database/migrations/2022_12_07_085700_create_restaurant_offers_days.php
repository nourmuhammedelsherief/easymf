<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantOffersDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_offers_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id');
            $table->foreignId('day_id');
            $table->timestamps();

            $table->foreign('offer_id')->references('id')->on('restaurant_offers')->onUpdate('cascade')->onDelete('cascade');
            
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
        Schema::dropIfExists('restaurant_offers_days');
    }
}
