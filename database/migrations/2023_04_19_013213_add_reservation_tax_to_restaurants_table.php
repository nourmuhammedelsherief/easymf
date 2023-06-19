<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReservationTaxToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('reservation_call_number' , 20 )->nullable()->after('reservation_description_en');

            $table->enum('reservation_is_call_phone' , ['true' , 'false'] )->default('false')->after('reservation_description_en');

            $table->string('reservation_whatsapp_number' , 20 )->nullable()->after('reservation_description_en');

            $table->enum('reservation_is_whatsapp' , ['true' , 'false'] )->default('false')->after('reservation_description_en');

            $table->float('reservation_tax_value' )->nullable()->after('reservation_description_en');

            $table->enum('reservation_tax' , ['true' , 'false'])->default('false')->after('reservation_description_en');
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['reservation_call_number' , 'reservation_is_call_phone' , 'reservation_whatsapp_number' , 'reservation_tax_value' , 'reservation_is_whatsapp' , 'reservation_tax']);
        });
    }
}
