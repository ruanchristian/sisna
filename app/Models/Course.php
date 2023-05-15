<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nome', 'cor_curso'];

    public function students() {
        return $this->hasMany(Student::class, 'curso_id');
    }
}
