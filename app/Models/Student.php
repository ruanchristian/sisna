<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Student extends Model implements AuditableContract {
    use HasFactory, Auditable;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'curso_id',
        'processo_id',
        'numero_inscricao',
        'origem',
        'media_pt',
        'media_mt',
        'media_final',
        'endereco',
        'numero',
        'cep',
        'bairro',
        'telefone_1',
        'telefone_2',
        'escola_origem_tipo',
        'escola_origem_nome',
        'responsavel_nome',
        'responsavel_parentesco',
        'doc_comprovante_idade',
        'doc_comprovante_residencia',
        'doc_historico',
        'inscrito_por',
    ];

    protected $casts = [
        'doc_comprovante_idade' => 'boolean',
        'doc_comprovante_residencia' => 'boolean',
        'doc_historico' => 'boolean',
    ];

    public function process() {
        return $this->belongsTo(SelectiveProcess::class, 'processo_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'curso_id');
    }

    public function inscritoPor() {
        return $this->belongsTo(User::class, 'inscrito_por');
    }

    public function isEscolaPublica(): bool {
        if ($this->escola_origem_tipo) return $this->escola_origem_tipo !== 'PARTICULAR';
        return str_starts_with($this->origem, 'PUBLICA');
    }
}
