<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Result extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    public $timestamps = false;

    public function student() {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }

    protected $fillable = [
        'student_id',
        'process_id',
        'is_classified',
        'origin',
        'course_id'
    ];
}