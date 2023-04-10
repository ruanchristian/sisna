<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectiveProcess extends Model {
    use HasFactory;

    protected $fillable = [
        'ano',
        'estado',
        'cursos'
    ];
}
