<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRestaurantLoyaltyPointsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_loyalty_points_history', function (Blueprint $table) {
            $table->dropForeign('restaurant_loyalty_points_history_order_id_foreign');
            $table->enum('order_type' , ['gold' , 'table'])->default('gold')->after('id');
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
            $table->foreign('order_id')->on('orders')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->dropColumn('order_type');
        });
    }
}
