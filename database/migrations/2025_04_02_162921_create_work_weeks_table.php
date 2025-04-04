<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_weeks', function (Blueprint $table) {
            $table->id()->start(1)->nocache();
            // $table->foreignId('user_id')->constrained();
            $table->date('inicio_semana');
            $table->date('fin_semana');
            $table->integer('total_hours')->default(0);
            $table->integer('excess_hours_area_a')->default(0);
            $table->integer('excess_hours_area_b')->default(0);
            $table->boolean('horas_movidas')->default(false);
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
        Schema::dropIfExists('work_weeks');
    }
}
