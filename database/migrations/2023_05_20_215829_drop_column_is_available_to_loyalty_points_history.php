<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnIsAvailableToLoyaltyPointsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_loyalty_points_history', function (Blueprint $table) {
            $table->dropColumn('is_available' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_loyalty_points_history', function (Blueprint $table) {
            $table->enum('is_available' , ['true' , 'false'])->default('true')->after('points');
        });
    }
}
