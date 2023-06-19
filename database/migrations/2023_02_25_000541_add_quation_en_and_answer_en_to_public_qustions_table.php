<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuationEnAndAnswerEnToPublicQustionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('public_questions', function (Blueprint $table) {
            $table->string('question_en')->nullable()->after('question');
            $table->string('answer_en')->nullable()->after('answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('public_questions', function (Blueprint $table) {
            $table->dropColumn(['question_en' , 'answer_en']);
        });
    }
}
