<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToToRestaurantAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_ads', function (Blueprint $table) {
            $table->enum('to' , ['restaurant' , 'website'])->default('website')->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_ads', function (Blueprint $table) {
            $table->dropColumn('to');
        });
    }
}
