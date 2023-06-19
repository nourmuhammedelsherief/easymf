<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->foreignId('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile' , 20)->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('eat_rate')->nullable();
            $table->tinyInteger('place_rate')->nullable();
            $table->tinyInteger('service_rate')->nullable();
            $table->tinyInteger('worker_rate')->nullable();
            $table->tinyInteger('speed_rate')->nullable();
            $table->tinyInteger('reception_rate')->nullable();
            $table->timestamps();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants_feedback');
    }
}
