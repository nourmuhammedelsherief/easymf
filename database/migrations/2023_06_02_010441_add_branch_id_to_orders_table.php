<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('whatsapp_branch_id')->nullable()->after('notes');
            $table->string('whatsapp_number')->nullable()->after('notes');


            $table->foreign('whatsapp_branch_id')->on('whatsapp_branches')->references('id')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('whatsapp_branch_id');
            $table->dropColumn('whatsapp_number');
        });
    }
}
