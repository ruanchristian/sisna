<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Course extends Model implements AuditableContract {
    use HasFactory, Auditable;

    public $timestamps = false;

    protected $fillable = ['nome', 'cor_curso'];

    public function students() {
        return $this->hasMany(Student::class, 'curso_id');
    }
}
