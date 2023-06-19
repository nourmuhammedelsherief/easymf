<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRestaurantsFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants_feedback', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('restaurant_id')->nullable();

            $table->foreign('branch_id')->references('id')->on('restaurant_feedback_branches')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants_feedback', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
}
