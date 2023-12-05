@extends('adminlte::page')

@section('title', 'Resultados - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Resultados dos processos seletivos</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                        <h4 class="text-center">ESCOLAS PÚBLICAS</h4><br>

                        @if ($publica->isEmpty())
                            <b class="text-danger">Não há alunos de escolas pública cadastrados.</b>
                        @else   
                        @foreach ($publica as $origin => $originResults)
                            @foreach ($originResults as $classified => $classifiedResults)
                                @if (!$classified) @break @endif

                                @foreach ($classifiedResults as $cursoId => $curso_r)
                                    <table border="1" width="100%">
                                        <thead>
                                            <tr>
                                                <td colspan="5">
                                                    <p class="text-center text-uppercase">
                                                        <u class="text-bold">RESULTADO PRELIMINAR</u><br>
                                                        <img width="75rem;" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                                                        EEEP DR JOSÉ ALVES DA SILVEIRA<br>
                                                        EDITAL 001/2023 - SELEÇÃO DE NOVOS ALUNOS 2023 - Escola
                                                        Pública<br>
                                                        CLASSIFICADOS DO CURSO TÉCNICO EM
                                                        {{ $cursos->find($cursoId)->nome }}<br>

                                                        @switch($origin)
                                                            @case('PCD')
                                                                <u>PESSOAS COM DEFICIÊNCIA</u>
                                                            @break

                                                            @case('PUBLICA-AMPLA')
                                                                <u>AMPLA CONCORRÊNCIA</u>
                                                            @break

                                                            @case('PUBLICA-PROX-EEEP')
                                                                <u>RESIDENTES PRÓXIMO A ESCOLA</u>
                                                            @break
                                                            @endswitch
                                                        </p>
                                                    </td>
                                                </tr>
                                                <th>Class</th>
                                                <th>Nome</th>
                                                <th>Média LP</th>
                                                <th>Média MT</th>
                                                <th>Média Final</th>
                                            </thead>

                                            <tbody>
                                                @foreach ($curso_r as $aluno)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}º</td>
                                                        <td>{{ $aluno->student->nome }}</td>
                                                        <td>{{ number_format($aluno->student->media_pt, 2, '.', '') }}</td>
                                                        <td>{{ number_format($aluno->student->media_mt, 2, '.', '') }}</td>
                                                        <td>{{ number_format($aluno->student->media_final, 2, '.', '') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table><br>
                                        {{-- <h6 class="text-right">Quixeramobi</h6><br><br> --}}

                                    @endforeach
                                @endforeach
                            @endforeach
                            <table border="1" width="100%">
                                <thead>
                                    <tr>
                                        <td colspan="7">
                                            <p class="text-center text-uppercase">
                                                <u class="text-bold">RESULTADO PRELIMINAR</u><br>
                                                <img width="75rem;" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                                                EEEP DR JOSÉ ALVES DA SILVEIRA<br>
                                                EDITAL 001/2023 - SELEÇÃO DE NOVOS ALUNOS 2023<br>
                                                CLASSIFICÁVEIS DAS ESCOLAS PÚBLICAS<br>
                                                </p>
                                            </td>
                                        </tr>
                                        <th>Class</th>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>Curso</th>
                                        <th>Média LP</th>
                                        <th>Média MT</th>
                                        <th>Média Final</th>
                                    </thead>

                                    <tbody>
                                        @foreach ($publicaClass as $alunos)
                                            @foreach ($alunos as $aluno)
                                            <tr>
                                                <td>{{ $loop->iteration }}º</td>
                                                <td>{{ $aluno->student->nome }}</td>
                                                <td>
                                                    @switch($aluno->student->origem)
                                                        @case('PUBLICA-AMPLA')
                                                            AMPLA CONCORRÊNICA
                                                            @break
                                                        @case('PUBLICA-PROX-EEEP')
                                                            RESIDENTES PRÓXIMOS
                                                        @break
                                                        @default
                                                            {{ $aluno->student->origem }}
                                                    @endswitch
                                                </td>
                                                <td>{{ $cursos->find($aluno->course_id)->nome }}</td>
                                                <td>{{ number_format($aluno->student->media_pt, 2, '.', '') }}</td>
                                                <td>{{ number_format($aluno->student->media_mt, 2, '.', '') }}</td>
                                                <td>{{ number_format($aluno->student->media_final, 2, '.', '') }}</td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table><br>  
                                @endif
                        </div>
                        <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                            <h4 class="text-center">ESCOLAS PARTICULARES</h4><br>
                            @if ($particular->isEmpty())
                                <b class="text-danger">Não há alunos de escola particular cadastrados.</b>
                            @else
                                @foreach ($particular as $origin => $originResults)
                                    @foreach ($originResults as $classified => $classifiedResults)
                                    @if (!$classified) @break @endif
                                     @foreach ($classifiedResults as $cursoId => $curso_r)
                                        <table border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <td colspan="5">
                                                        <p class="text-center text-uppercase">
                                                            <u class="text-bold">RESULTADO PRELIMINAR</u><br>
                                                            <img width="75rem;" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                                                            EEEP DR JOSÉ ALVES DA SILVEIRA<br>
                                                            EDITAL 001/2023 - SELEÇÃO DE NOVOS ALUNOS 2023 - Escolas
                                                            Particulares<br>
                                                            CLASSIFICADOS DO CURSO TÉCNICO EM
                                                            {{ $cursos->find($cursoId)->nome }}<br>

                                                            @switch($origin)
                                                                @case('PRIVATE-AMPLA')
                                                                    <u>AMPLA CONCORRÊNCIA</u>
                                                                @break

                                                                @case('PRIVATE-PROX-EEEP')
                                                                    <u>RESIDENTES PRÓXIMO A ESCOLA</u>
                                                                @break
                                                            @endswitch
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <th>Class</th>
                                                    <th>Nome</th>
                                                    <th>Média LP</th>
                                                    <th>Média MT</th>
                                                    <th>Média Final</th>
                                                </thead>

                                                <tbody>
                                                    @foreach ($curso_r as $aluno)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}º</td>
                                                            <td>{{ $aluno->student->nome }}</td>
                                                            <td>{{ number_format($aluno->student->media_pt, 2, '.', '') }}</td>
                                                            <td>{{ number_format($aluno->student->media_mt, 2, '.', '') }}</td>
                                                            <td>{{ number_format($aluno->student->media_final, 2, '.', '') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table><br>
                                            {{-- <p>Quixeramobim</p><br> --}}
                                        @endforeach
                                    @endforeach
                                @endforeach
                                <table border="1" width="100%">
                                    <thead>
                                        <tr>
                                            <td colspan="7">
                                                <p class="text-center text-uppercase">
                                                    <u class="text-bold">RESULTADO PRELIMINAR</u><br>
                                                    <img width="75rem;" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                                                    EEEP DR JOSÉ ALVES DA SILVEIRA<br>
                                                    EDITAL 001/2023 - SELEÇÃO DE NOVOS ALUNOS 2023<br>
                                                    CLASSIFICÁVEIS DAS ESCOLAS PARTICULARES<br>
                                                    </p>
                                                </td>
                                            </tr>
                                            <th>Class</th>
                                            <th>Nome</th>
                                            <th>Categoria</th>
                                            <th>Curso</th>
                                            <th>Média LP</th>
                                            <th>Média MT</th>
                                            <th>Média Final</th>
                                        </thead>
    
                                        <tbody>
                                            @foreach ($particularClass as $alunos)
                                                @foreach ($alunos as $aluno)
                                                <tr>
                                                    <td>{{ $loop->iteration }}º</td>
                                                    <td>{{ $aluno->student->nome }}</td>
                                                    <td>
                                                    @switch($aluno->student->origem)
                                                        @case('PRIVATE-AMPLA')
                                                            AMPLA CONCORRÊNCIA
                                                        @break

                                                        @case('PRIVATE-PROX-EEEP')
                                                            RESIDENTES PRÓXIMO A ESCOLA
                                                        @break
                                                    @endswitch
                                                    </td>
                                                    <td>{{ $cursos->find($aluno->course_id)->nome }}</td>
                                                    <td>{{ number_format($aluno->student->media_pt, 2, '.', '') }}</td>
                                                    <td>{{ number_format($aluno->student->media_mt, 2, '.', '') }}</td>
                                                    <td>{{ number_format($aluno->student->media_final, 2, '.', '') }}</td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table><br> 
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@stop