<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("task_id");
            $table->string("task_header");
            $table->string("staff_id");
            $table->string("staff_name");
            $table->integer("viewed");
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
        Schema::dropIfExists('task_notifications');
    }
}
