<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBankTransterRestaurantOrderSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_order_settings', function (Blueprint $table) {
            $table->enum('bank_transfer'  , ['true' , 'false'])->default('false')->after('receipt_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_order_settings', function (Blueprint $table) {
            $table->dropColumn('bank_transfer');
        });
    }
}
