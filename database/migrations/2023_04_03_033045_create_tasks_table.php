<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('employee_id');
            $table->text('description');
            $table->double('hours_count')->default(1)->nullable();
            $table->datetime('worked_at')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->string('admin_name')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('employee_id')->references('id')->on('task_employees')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('admin_id')->references('id')->on('admins')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
