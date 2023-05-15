<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialConfig extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vagas_pcd',
        'vagas_publica_ampla',
        'vagas_publica_prox',
        'vagas_private_ampla',
        'vagas_private_prox',
        'ordem_desempate',
        'processo_id'
    ];

    public function process() {
        return $this->belongsTo(SelectiveProcess::class, 'processo_id');
    }
}
