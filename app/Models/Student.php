<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    use HasFactory;

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
}
