<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductPriceToSliverOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('silver_orders', function (Blueprint $table) {
            $table->float('product_price')->nullable()->after('product_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('silver_orders', function (Blueprint $table) {
            $table->dropColumn('product_price');
        });
    }
}
