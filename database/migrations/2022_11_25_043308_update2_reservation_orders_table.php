<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update2ReservationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_orders', function (Blueprint $table) {
            $table->boolean('is_order')->after('total_price')->comment('if client completed all data')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_orders', function (Blueprint $table) {
            $table->dropColumn('is_order');
        });
    }
}
