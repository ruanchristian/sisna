@extends('adminlte::page')

@section('title', 'Resultados - ')

@push('css')
    <style>
        th {
            background-color: #b3b3b3;
        }
    </style>
@endpush

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="mb-0">Resultados do processo seletivo de {{ $ano }}</h1>
            </div>
            @if ($publica->isNotEmpty() || $particular->isNotEmpty())
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <x-adminlte-button icon="fas fa-file-pdf" label="Resultado Geral em PDF" theme="danger"
                            data-toggle="modal" data-target="#modalOpt" />
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div id="tbl_res">
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
                                        'flag' => false,
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
                                        'flag' => false,
                                    ])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-adminlte-modal id="modalOpt" title="Opções para gerar PDF" theme="danger" icon="fas fa-file-pdf" static-backdrop>
        <form id="pdfForm">
            <x-adminlte-input name="edital" label="Número do Edital:" placeholder="Ex: 01/2023" required />

            <x-adminlte-select name="tipo_resultado" label="Tipo do Resultado:" required>
                <option value="PRELIMINAR">Preliminar</option>
                <option value="FINAL">Final</option>
            </x-adminlte-select>

            <x-adminlte-input name="dia" label="Dia:" type="number" min="1" max="31" required />
            <x-adminlte-input name="mes" label="Mês:" placeholder="Ex: Janeiro" required />
            <x-adminlte-input name="year" label="Ano:" type="number" min="2011" max="2099" placeholder="Ex: 2023" required />
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button class="gerar_pdf" label="Gerar PDF" theme="primary" icon="fas fa-check" />
        </x-slot>
    </x-adminlte-modal>


    {{-- Form oculto para criptografia/serialização das coleções e transfere ao backend pra gerar PDF único --}}
    <form id="formpdf" method="POST" action="{{ route('resultado.pdf') }}">
        @csrf
        <input type="hidden" name="ano" value="{{ $ano }}">
        <input type="hidden" name="cursos" value="{{ base64_encode(serialize($cursos)) }}">
        <input type="hidden" name="publica" value="{{ base64_encode(serialize($publica)) }}">
        <input type="hidden" name="particular" value="{{ base64_encode(serialize($particular)) }}">
        <input type="hidden" name="publicaClassificaveis" value="{{ base64_encode(serialize($publicaClassificaveis)) }}">
        <input type="hidden" name="particularClassificaveis"
            value="{{ base64_encode(serialize($particularClassificaveis)) }}">
        <input type="hidden" name="editall">
        <input type="hidden" name="resultado">
        <input type="hidden" name="day">
        <input type="hidden" name="month">
        <input type="hidden" name="yearr">
    </form>
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('.gerar_pdf').click(function() {
                const edital = document.querySelector('input[name="edital"]').value;
                const resultado = document.querySelector('select[name="tipo_resultado"]').value;
                const dia = document.querySelector('input[name="dia"]').value;
                const mes = document.querySelector('input[name="mes"]').value;
                const ano = document.querySelector('input[name="year"]').value;

                document.querySelector('input[name="editall"]').value = edital;
                document.querySelector('input[name="resultado"]').value = resultado;
                document.querySelector('input[name="day"]').value = dia;
                document.querySelector('input[name="month"]').value = mes;
                document.querySelector('input[name="yearr"]').value = ano;

                document.getElementById('formpdf').submit();
            })

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
