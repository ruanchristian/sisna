<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 50);
            $table->date('data_nascimento');
            $table->unsignedInteger('curso_id');
            $table->unsignedInteger('processo_id');
            $table->unsignedFloat('media_pt');
            $table->unsignedFloat('media_mt');
            $table->unsignedFloat('media_final');
            $table->enum('origem', ['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP', 'PCD']);
            $table->foreign('curso_id')->references('id')->on('courses');
            $table->foreign('processo_id')->references('id')->on('selective_processes');
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
        Schema::dropIfExists('students');
    }
};