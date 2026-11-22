<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campos da ficha de inscrição (antes preenchida à mão).
     * Todos são nullable para não quebrar os alunos já cadastrados.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('numero_inscricao')->nullable()->after('processo_id');

            $table->string('endereco', 120)->nullable()->after('data_nascimento');
            $table->string('numero', 10)->nullable()->after('endereco');
            $table->string('cep', 9)->nullable()->after('numero');
            $table->string('bairro', 80)->nullable()->after('cep');
            $table->string('telefone_1', 20)->nullable()->after('bairro');
            $table->string('telefone_2', 20)->nullable()->after('telefone_1');

            // Escola de origem
            $table->enum('escola_origem_tipo', ['ESTADUAL', 'MUNICIPAL', 'PARTICULAR'])->nullable()->after('telefone_2');
            $table->string('escola_origem_nome', 120)->nullable()->after('escola_origem_tipo');

            // Termo de ciência
            $table->string('responsavel_nome', 100)->nullable()->after('escola_origem_nome');
            $table->string('responsavel_parentesco', 30)->nullable()->after('responsavel_nome');

            // Entrega de documentos
            $table->boolean('doc_comprovante_idade')->default(false)->after('responsavel_parentesco');
            $table->boolean('doc_comprovante_residencia')->default(false)->after('doc_comprovante_idade');
            $table->boolean('doc_historico')->default(false)->after('doc_comprovante_residencia');

            // Funcionário que fez a inscrição
            $table->foreignId('inscrito_por')->nullable()->after('origem')
                  ->constrained('users')->nullOnDelete();
        });

        // Numera os alunos já existentes, por processo, na ordem de cadastro.
        $processos = DB::table('students')->distinct()->pluck('processo_id');
        foreach ($processos as $processoId) {
            $ids = DB::table('students')->where('processo_id', $processoId)->orderBy('id')->pluck('id');
            foreach ($ids as $i => $id) {
                DB::table('students')->where('id', $id)->update(['numero_inscricao' => $i + 1]);
            }
        }

        Schema::table('students', function (Blueprint $table) {
            $table->unique(['processo_id', 'numero_inscricao']);
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('processo_id', 'students_processo_id_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['processo_id', 'numero_inscricao']);
            $table->dropForeign(['inscrito_por']);
            $table->dropColumn([
                'numero_inscricao', 'endereco', 'numero', 'cep', 'bairro', 'telefone_1', 'telefone_2',
                'escola_origem_tipo', 'escola_origem_nome', 'responsavel_nome', 'responsavel_parentesco',
                'doc_comprovante_idade', 'doc_comprovante_residencia', 'doc_historico', 'inscrito_por',
            ]);
        });
    }
};
