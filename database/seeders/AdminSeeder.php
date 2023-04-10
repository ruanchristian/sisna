<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
Use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run() {
        User::factory()->create([
            'name' => 'Ruan Christian',
            'email' => 'christianruan94@gmail.com',
            'password' => bcrypt('password'),
            'type' => 'administrador'
        ]);
    }
}
