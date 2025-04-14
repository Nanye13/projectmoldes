<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoldesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moldes', function (Blueprint $table) {
            $table->id()->start(1)->nocache();
            $table->string('nombre');
            $table->enum('tipo_mantenimiento', ['A', 'B']);
            $table->integer('horas');
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
        Schema::dropIfExists('moldes');
    }
}
