<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFoodicsStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('foodics_status')->nullable()->after('foodics_order_id');
        });
        Schema::table('table_orders', function (Blueprint $table) {
            $table->integer('foodics_status')->nullable()->after('foodics_order_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('foodics_status');
        });
        Schema::table('table_orders', function (Blueprint $table) {
            $table->dropColumn('foodics_status');
        });
    }
}
