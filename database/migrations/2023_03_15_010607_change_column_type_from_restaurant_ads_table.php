<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeFromRestaurantAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_ads', function (Blueprint $table) {
            DB::select("ALTER TABLE `restaurant_ads` CHANGE `type` `type` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'main';
            ");
            DB::select("ALTER TABLE `restaurant_ads` CHANGE `restaurant_id` `restaurant_id` BIGINT(20) UNSIGNED NULL;");
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
            //
        });
    }
}
