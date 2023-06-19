<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumntPackageIdToSellerCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_codes', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->after('custom_url');

            $table->foreign('package_id')->references('id')->on('packages')->onUpdate('cascade')->onDelete('set null');
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
            $table->dropConstrainedForeignId('package_id');
        });
    }
}
