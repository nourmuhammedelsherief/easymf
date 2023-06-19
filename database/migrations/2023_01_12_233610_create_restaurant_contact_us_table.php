<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_contact_us', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort')->default(1);
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
        Schema::dropIfExists('restaurant_contact_us');
    }
}
