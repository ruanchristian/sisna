<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectiveProcess extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['ano', 'estado', 'cursos'];

    public function students() {
        return $this->hasMany(Student::class, 'processo_id');
    }
}
