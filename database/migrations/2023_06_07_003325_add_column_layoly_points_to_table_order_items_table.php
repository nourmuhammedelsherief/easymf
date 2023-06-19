<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLayolyPointsToTableOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_order_items', function (Blueprint $table) {
            $table->integer('loyalty_points')->nullable()->unsigned()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_order_items', function (Blueprint $table) {
            $table->dropColumn('loyalty_points');
        });
    }
}
