<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumntTypeToSellerCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_codes', function (Blueprint $table) {
            $table->string('custom_url')->nullable()->after('marketer_id');
            $table->enum('used_type' , ['code' , 'url'])->default('code')->after('marketer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_codes', function (Blueprint $table) {
            $table->dropColumn(['used_type' , 'custom_url']);
        });
    }
}
