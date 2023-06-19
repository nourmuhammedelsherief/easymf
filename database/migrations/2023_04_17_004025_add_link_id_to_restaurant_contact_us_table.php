<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkIdToRestaurantContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_contact_us', function (Blueprint $table) {
            $table->foreignId('link_id')->nullable()->after('restaurant_id');

            $table->foreign('link_id')->on('restaurant_contact_us_links')->references('id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_contact_us', function (Blueprint $table) {
            $table->dropConstrainedForeignId('link_id');
        });
    }
}
