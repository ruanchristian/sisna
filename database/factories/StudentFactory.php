<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $origins = ['PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP', 'PCD'];
        $media_pt = mt_rand(2*10, 10*10) / 10;
        $media_mt = mt_rand(2*10, 10*10) / 10;

        return [
            'nome' => strtoupper(fake()->name()),
            'data_nascimento' => $this->randomDate('2005-01-01', '2005-12-31'),
            'curso_id' => rand(1, 4),
            'processo_id' => 1,
            'origem' => $origins[rand(0, 4)],
            'media_pt' => $media_pt,
            'media_mt' => $media_mt,
            'media_final' => mt_rand(((int) $media_mt)*10, 100) / 10
        ];
    }

    private function randomDate($min, $max) {
        return date('Y-m-d', rand(strtotime($min), strtotime($max)));
    }
}
