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
        'origem',
        'media_pt',
        'media_mt',
        'media_final'
    ];

    public function process() {
        return $this->belongsTo(SelectiveProcess::class, 'processo_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'curso_id');
    }
}