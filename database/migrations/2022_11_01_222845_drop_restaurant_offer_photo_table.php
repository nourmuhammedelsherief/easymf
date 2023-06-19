<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRestaurantOfferPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('restaurant_offer_photos');
        Schema::table('restaurant_offers', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('restaurant_offer_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_offer_id');
            $table->string('photo');
            $table->timestamps();

            $table->foreign('restaurant_offer_id')->references('id')->on('restaurant_offers')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('restaurant_offers', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}
