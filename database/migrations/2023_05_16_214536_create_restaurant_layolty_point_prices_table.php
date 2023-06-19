<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantLayoltyPointPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_loyalty_points_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->integer('points');
            $table->float('price');
            $table->timestamps();

            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_loyalty_point_prices');
    }
}
