<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTimeInRestaurantOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_offers', function (Blueprint $table) {
            $table->time('start_at')->nullable()->after('photo');
            $table->time('end_at')->nullable()->after('photo');
            $table->enum('time' , ['true' , 'false'])->default('false')->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_offers', function (Blueprint $table) {
            $table->dropColumn('time' , 'start_at' ,'end_at');
        });
    }
}
