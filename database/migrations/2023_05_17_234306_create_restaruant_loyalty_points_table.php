<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaruantLoyaltyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaruant_loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->enum('type' , ['point' , 'balance'])->default('point');
            $table->foreignId('user_id');
            $table->foreignId('restaurant_id')->nullable();
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->on('users')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaruant_loyalty_points');
    }
}
