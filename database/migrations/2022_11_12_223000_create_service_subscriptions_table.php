<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable();
            $table->string('restaurant_name');
            $table->string('restaurant_phone');
            $table->foreignId('service_id');
            $table->double('price');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('services')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_subscriptions');
    }
}
