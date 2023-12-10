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
        Schema::create('special_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('vagas_pcd')->default(2);
            $table->integer('vagas_publica_ampla')->default(25);
            $table->integer('vagas_publica_prox')->default(10);
            $table->integer('vagas_private_ampla')->default(6);
            $table->integer('vagas_private_prox')->default(2);
            $table->json('ordem_desempate')->default();

            $table->unsignedInteger('processo_id');
            $table->foreign('processo_id')->references('id')->on('selective_processes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_configs');
    }
};
