<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRestaurantIdToWhatsappBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_branches', function (Blueprint $table) {
            $table->foreignId('restaurant_id');
            $table->foreign('restaurant_id')->on('restaurants')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('whatsapp_branches', function (Blueprint $table) {
            $table->dropColumn('restaurant_id');
        });
    }
}
