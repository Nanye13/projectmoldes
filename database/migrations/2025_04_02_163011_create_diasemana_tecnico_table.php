<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiasemanaTecnicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diasemana_tecnico', function (Blueprint $table) {
            $table->id()->start(1)->nocache();
            $table->foreignId('work_week_id')->constrained();
            $table->date('dia_semana');
            $table->foreignId('tecnico_id')->constrained();
            $table->integer('horas'); // 5 o 7 normalmente
            $table->integer('estatus');
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
        Schema::dropIfExists('diasemana_tecnico');
    }
}
