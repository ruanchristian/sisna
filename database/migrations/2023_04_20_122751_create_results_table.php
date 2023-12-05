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
        Schema::create('results', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->foreignId('student_id')->constrained('students');
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('course_id');
            $table->boolean('is_classified')->default(false);
            $table->enum('origin', ['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP', 'PCD']);

            $table->foreign('process_id')->references('id')->on('selective_processes');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
};