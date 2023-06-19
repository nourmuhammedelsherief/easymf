<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTypeInRestaurantSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_sliders', function (Blueprint $table) {
            DB::select("ALTER TABLE `restaurant_sliders` CHANGE `type` `type` ENUM('image','youtube','gif','local_video') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image';
            ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_sliders', function (Blueprint $table) {
            DB::select("ALTER TABLE `restaurant_sliders` CHANGE `type` `type` ENUM('image','youtube') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image';
            ");
        });
    }
}
