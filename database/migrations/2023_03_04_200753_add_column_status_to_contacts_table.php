<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_contact_us', function (Blueprint $table) {
            $table->enum('status' , ['true' , 'false'])->default('true')->after('title_en');
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
            $table->dropColumn('status');
        });
    }
}
