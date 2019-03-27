<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialDisciplineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('financial_discipline', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->integer("task_id");
            $table->string("name");
            $table->string("salary");
            $table->string("fine");
            $table->string("remaining_balance");
            $table->string("day_of_the_month");
            $table->string("day");
            $table->string("month");
            $table->string("year");
            $table->string("staff_punishement_type");
            $table->string("admin_complaints")->nullable();
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
        Schema::dropIfExists('financial_discipline');
    }
}
