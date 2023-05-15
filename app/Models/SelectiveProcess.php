<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SelectiveProcess extends Model {
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['ano', 'estado', 'cursos'];

    protected static function boot() {
        parent::boot();

        static::created(function ($process) {
            $process->config()->create();
        });
    }

    public function students() {
        return $this->hasMany(Student::class, 'processo_id');
    }

    public function config() {
        return $this->hasOne(SpecialConfig::class, 'processo_id');
    }
}