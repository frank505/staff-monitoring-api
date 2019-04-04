<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffLoginDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_login_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("staff_id");
            $table->string("staff_name");
            $table->string("date");
            $table->string("time");
            $table->string("day");
            $table->string("month");
            $table->string("year");
            $table->integer("admin_recieve_push");
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
        Schema::dropIfExists('staff_login_detail');
    }
}
