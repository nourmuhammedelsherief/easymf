<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRestaurantIdToMarketerOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketer_operations', function (Blueprint $table) {
            $table->foreignId('restaurant_id')->nullable()->after('marketer_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marketer_operations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('restaurant_id');
        });
    }
}
