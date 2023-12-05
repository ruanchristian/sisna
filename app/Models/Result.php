<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

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