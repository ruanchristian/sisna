<?php

use App\Services\Selection\RedistributionGraph;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Grafo de redistribuição de vagas remanescentes (lista de adjacência em JSON).
     * Processos existentes recebem a regra que estava fixa no código.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_configs', function (Blueprint $table) {
            $table->json('redistribuicao')->nullable()->after('ordem_desempate');
        });

        DB::table('special_configs')->update([
            'redistribuicao' => json_encode(RedistributionGraph::DEFAULT_EDGES),
        ]);
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('special_configs', function (Blueprint $table) {
            $table->dropColumn('redistribuicao');
        });
    }
};
