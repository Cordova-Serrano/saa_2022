<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            $table->unsignedBigInteger('career_id');
            $table->foreign('career_id')->references('id')->on('careers');

            $table->string('generation');
            $table->integer('uaslp_key')->unique(); //Clave única
            $table->bigInteger('large_key')->unique(); //Clave larga o de ingeniería
            $table->integer('type')->nullable(); //Tipo: 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}
