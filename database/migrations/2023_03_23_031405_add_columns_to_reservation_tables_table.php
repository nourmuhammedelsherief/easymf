<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToReservationTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservation_tables', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('status');
            $table->string('title_ar')->nullable()->after('status');
            $table->text('description_en')->nullable()->after('status');
            $table->text('description_ar')->nullable()->after('status');
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
            $table->dropColumn(['title_en' , 'title_ar' , 'description_en' , 'description_ar']);
        });
    }
}
