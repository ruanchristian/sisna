<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$cursos = [
    1 => 14, // adm
    2 => 11, // edf
    3 => 12, // infor
    4 => 13 // nutri (Log = 15)
];

$origensId = [
    'PUBLICA-AMPLA' => 1,
    'PUBLICA-PROX-EEEP' => 3,
    'PRIVATE-AMPLA' => 2,
    'PRIVATE-PROX-EEEP' => 4,
    'PCD' => 5
];

DB::connection('sisna_db')->table('students')->chunk(200, function ($students) use ($cursos, $origensId) {
    // Mapeando colunas
    $mappedStudents = $students->map(function ($student) use ($cursos, $origensId) {
        return [
            'nome' => $student->nome,
            'data_nascimento' => $student->data_nascimento,
            'primeira_opcao' => $cursos[$student->curso_id],
            'segunda_opcao' => 10,
            'processo' => 22,
            'nota_pt' => $student->media_pt,
            'nota_mt' => $student->media_mt,
            'media' => $student->media_final,
            'origem' => $origensId[$student->origem],
        ];
    });

    DB::connection('db_selecao')->table('aluno')->insert($mappedStudents->toArray());
});

echo "Dados migrados com sucesso!\n";