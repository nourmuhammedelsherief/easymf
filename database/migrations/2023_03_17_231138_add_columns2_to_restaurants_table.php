<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns2ToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->enum('enable_reservation_online_pay' , ['true' , 'false'])->default('true')->after('enable_bank'); 
            $table->enum('enable_reservation_bank' , ['true' , 'false'])->default('true')->after('enable_bank'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['enable_reservation_online_pay' , 'enable_reservation_bank']);
        });
    }
}
