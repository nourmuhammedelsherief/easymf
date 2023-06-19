<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTimeInRestaurantAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_ads', function (Blueprint $table) {
            $table->time('start_at')->nullable()->after('end_date');
            $table->time('end_at')->nullable()->after('end_date');
            $table->enum('time' , ['true' , 'false'])->default('false')->after('end_date');
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
            $table->dropColumn('time' , 'start_at' , 'end_at');
        });
    }
}
