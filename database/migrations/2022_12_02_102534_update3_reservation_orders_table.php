<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update3ReservationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_orders', function (Blueprint $table) {
            $table->string('user_phone' , 20)->after('user_id');
            $table->string('user_name')->after('user_id');
            $table->dropForeign('reservation_orders_ibfk_6');
            $table->foreign('user_id' , 'reservation_orders_ibfk_6')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
            $table->dropColumn(['user_name' , 'user_phone']);
            $table->dropForeign('reservation_orders_ibfk_6');
            $table->foreign('user_id' , 'reservation_orders_ibfk_6')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }
}
