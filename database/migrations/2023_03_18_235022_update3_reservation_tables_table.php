<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update3ReservationTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_tables', function (Blueprint $table) {
            $table->enum('type' , ['table' , 'chair' , 'package'])->default('table')->after('reservation_place_id');
            $table->integer('chair_min')->nullable()->after('table_count');
            $table->integer('chair_max')->nullable()->after('table_count');
            $table->string('image')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservation_tables', function (Blueprint $table) {
            $table->dropColumn(['type' , 'chair_min' ,'chair_max' , 'image']);
        });
    }
}
