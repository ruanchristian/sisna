<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'curso',
        'processo',
        'origem',
        'media_pt',
        'media_mt',
        'media_final'
    ];
}
