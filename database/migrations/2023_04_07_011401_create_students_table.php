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
            $table->unsignedInteger('curso');
            $table->unsignedInteger('processo');
            $table->unsignedDouble('media_pt');
            $table->unsignedDouble('media_mt');
            $table->unsignedDouble('media_final');
            $table->enum('origem', ['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP', 'PCD']);
            $table->foreign('curso')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('processo')->references('id')->on('selective_processes')->onDelete('cascade');
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
