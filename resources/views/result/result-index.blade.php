@extends('adminlte::page')

@section('title', 'Resultados - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Resultados do processo seletivo de {{ $ano }}</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                        <h4 class="text-center text-bold">ESCOLAS PÚBLICAS</h4><br>

                        @if ($publica->isEmpty())
                            <b class="text-danger">Não existem alunos cadastrados de escola pública!</b>
                        @else
                        @foreach ($publica as $cursoId => $classfv)
                            @php $curso = $cursos->find($cursoId)->nome; @endphp
                            @foreach ($classfv as $origin => $alunos)
                                @include('result.tabela', [
                                    'ano' => $ano,
                                    'cursoNome' => $curso,
                                    'alunos' => $alunos,
                                    'origin' => $origin,
                                    'type' => 'Pública',
                                    'flag' => true,
                                ])   
                            @endforeach
                        @endforeach
                        @foreach ($publicaClassificaveis as $cursoId => $alunos)
                            @php $curso = $cursos->find($cursoId)->nome; @endphp
                            @include('result.tabela', [
                                'ano' => $ano,
                                'cursoNome' => $curso,
                                'alunos' => $alunos,
                                'type' => 'Pública',
                                'flag' => false
                            ])
                        @endforeach
                    @endif
                </div>
                <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                    <h4 class="text-center text-bold">ESCOLAS PARTICULARES</h4><br>
                    @if ($particular->isEmpty())
                        <b class="text-danger">Não existem alunos cadastrados de escola particular!</b>
                    @else
                    @foreach ($particular as $cursoId => $classfv)
                        @php $curso = $cursos->find($cursoId)->nome; @endphp
                        @foreach ($classfv as $origin => $alunos)
                            @include('result.tabela', [
                                'ano' => $ano,
                                'cursoNome' => $curso,
                                'alunos' => $alunos,
                                'origin' => $origin,
                                'type' => 'Particular',
                                'flag' => true,
                            ])   
                        @endforeach
                    @endforeach
                    @foreach ($particularClassificaveis as $cursoId => $alunos)
                        @php $curso = $cursos->find($cursoId)->nome; @endphp
                        @include('result.tabela', [
                            'ano' => $ano,
                            'cursoNome' => $curso,
                            'alunos' => $alunos,
                            'type' => 'Particular',
                            'flag' => false
                        ])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@stop

@push('js')
<script>
    $(document).ready(function() {
        $('.print').click(function() {
            let ref = $(this).attr('id');
            let resultado = document.getElementById(ref).innerHTML;
            let telinha = window.open();
            telinha.document.write(resultado);

            setTimeout(() => {
                telinha.print();
                telinha.close();
            }, 200);
        })
    });
</script>
@endpush
