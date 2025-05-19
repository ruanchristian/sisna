<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultados {{ $ano }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 15px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 2px; text-align: center;  }
        th { background-color: #b3b3b3; }
    </style>
</head>
<body>

@foreach ($cursos as $curso)
    @foreach (['PCD', 'PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-AMPLA', 'PRIVATE-PROX-EEEP'] as $categoria)
        @if (!$classificados[$curso->id][$categoria]->isEmpty())
            @include('result.tabela-pdf', [
                'ano' => $ano,
                'cursoNome' => $curso->nome,
                'alunos' => $classificados[$curso->id][$categoria],
                'origin' => $categoria,
                'type' => str_contains($categoria, 'PRIVATE') ? 'Particular' : 'Pública',
                'flag' => true,
            ])
        @endif
    @endforeach

    {{-- Classificáveis - Escola Pública --}}
    @if (!empty($publica_classfv[$curso->id]) && $publica_classfv[$curso->id]->count())
        @include('result.tabela-pdf', [
            'alunos' => $publica_classfv[$curso->id],
            'flag' => false,
            'origin' => null,
            'cursoNome' => $curso->nome,
            'ano' => $ano,
            'type' => 'Pública'
        ])
    @endif

    {{-- Classificáveis - Escola Particular --}}
    @if (!empty($private_classfv[$curso->id]) && $private_classfv[$curso->id]->count())
        @include('result.tabela-pdf', [
            'alunos' => $private_classfv[$curso->id],
            'flag' => false,
            'origin' => null,
            'cursoNome' => $curso->nome,
            'ano' => $ano,
            'type' => 'Privada'
        ])
    @endif
@endforeach
</body>
</html>