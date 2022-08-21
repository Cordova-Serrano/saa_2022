<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->string('status'); //Situación: Inscrito, Rezago
            $table->integer('creds_remaining'); //Créditos por cursar
            $table->integer('creds_per_semester'); //Créditos por semestre
            $table->integer('semesters_completed'); //Semestres cursados
            $table->double('percentage_progress'); //Porcentaje de avance
            $table->float('general_average',5,3); //Promedio general
            $table->double('general_performance',5,3); //Rendimiento general 
            $table->double('app_average',5,3); //Promedio aprobatorio
            $table->integer('subjects_approved'); //Materias aprobadas
            $table->integer('subjects_failed'); //Materias reprobadas
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
