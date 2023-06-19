<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantContactUsLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_contact_us_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('barcode')->unique();
            $table->enum('status' , ['true' , 'false'])->default('true');
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
        Schema::dropIfExists('restaurant_contact_us_links');
    }
}
