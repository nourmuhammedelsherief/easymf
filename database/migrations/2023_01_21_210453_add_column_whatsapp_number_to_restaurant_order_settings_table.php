<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWhatsappNumberToRestaurantOrderSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_order_settings', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable()->after('delivery_value');
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
            $table->dropColumn('whatsapp_number');
        });
    }
}
