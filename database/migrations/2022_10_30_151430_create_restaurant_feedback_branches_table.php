<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantFeedbackBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_feedback_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('link')->nullable();
            $table->integer('sort')->default(1);
            $table->timestamps();

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
        Schema::dropIfExists('restaurant_feedback_branches');
    }
}
