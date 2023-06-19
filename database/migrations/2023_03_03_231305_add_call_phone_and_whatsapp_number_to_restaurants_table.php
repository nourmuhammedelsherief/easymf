<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallPhoneAndWhatsappNumberToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('call_phone')->nullable()->after('default_lang');
            $table->string('whatsapp_number')->nullable()->after('default_lang');
            $table->enum('is_call_phone' , ['true' , 'false'])->default('false')->after('default_lang');
            $table->enum('is_whatsapp' , ['true' , 'false'])->default('false')->after('default_lang');

            
            

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
            $table->dropColumn(['is_call_phone' , 'call_phone' , 'whatsapp_number' , 'is_whatsapp']);
        });
    }
}
