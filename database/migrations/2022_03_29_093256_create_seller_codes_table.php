<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->enum('active' , ['true' , 'false'])->default('true');
            $table->double('percentage')->default(0);
            $table->double('code_percentage')->default(0);
            $table->double('commission')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_codes');
    }
}
