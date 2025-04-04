<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id()->start(1)->nocache();
            $table->foreignId('work_week_id')->constrained();
            $table->foreignId('molde_id')->constrained();
            $table->integer('tecnico_id');
            $table->enum('area', ['A', 'B']);
            $table->integer('priority')->unsigned(); // 1-5
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('estimated_hours');
            $table->integer('actual_hours')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('completed')->default(false);
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
        Schema::dropIfExists('taks');
    }
}
