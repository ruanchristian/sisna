<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{

    protected $fillable = ['versao', 'data', 'mudancas'];

    protected $casts = [
        'data' => 'date',
        'mudancas' => 'array',
    ];
}
