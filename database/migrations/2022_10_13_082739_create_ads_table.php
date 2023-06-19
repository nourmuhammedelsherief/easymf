<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->foreignId('category_id')->nullable();
            $table->enum('type' , ['main' , 'menu_category']);
            $table->enum('content_type' , ['image' , 'youtube']);
            $table->string('content');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('category_id')->references('id')->on('menu_categories')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_ads');
    }
}
