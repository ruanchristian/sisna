@extends('adminlte::page')

@section('title', 'Painel - ')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Painel</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <span class="breadcrumb-item">Bem vindo(a): <b>{{ Auth::user()->name }}</b></span>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $processesCount }}</h3>
                        <p>Processos seletivos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrows-down-to-people"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('process.index') }}" class="small-box-footer"> Ver mais
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $coursesCount }}</h3>
                        <p>Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('course.index') }}" class="small-box-footer"> Ver mais <i
                                class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $studentsCount > 99999 ? '99999+' : $studentsCount }}</h3>
                        <p>Alunos cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="javascript:void(0);" class="small-box-footer"> Ver mais <i
                                class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $adminsCount }}</h3>
                        <p>{{ $adminsCount == 1 ? 'Administrador' : 'Administradores' }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-code"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('user.index') }}" class="small-box-footer"> Ver mais <i
                                class="fas fa-arrow-circle-right"></i></a>
                    @endcan
                </div>
            </div>
        </div>

        @cannot('isAdmin', Auth::user())
            <x-adminlte-card title="Processos seletivos em andamento" theme="primary" icon="fas fa-arrows-down-to-people">
                @if ($ativos->isEmpty())
                    <b class="text-danger">Não há processos em andamento.</b>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ano</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ativos as $p)
                                <tr>
                                    <td>{{ $p->ano }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle drop-d" type="button"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-user-graduate"></i>
                                                Participantes
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('student.index', $p->id) }}">Cadastrar
                                                        participantes</a></li>
                                                <li><a class="dropdown-item" href="{{ route('student.visualization', $p->id) }}">Ver participantes</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </x-adminlte-card>
        @endcannot
        {{-- 
        <div class="row mt-5">
            <div class="col-md-6">
                <x-adminlte-card title="Gráfico 1" theme="primary" icon="fas fa-lg fa-chart-pie">
                    <canvas height="300px" id="grafico1"></canvas>
                </x-adminlte-card>
            </div>

            <div class="col-md-6">
                <x-adminlte-card title="Gráfico 2" theme="primary" icon="fas fa-lg fa-chart-simple">
                    <canvas height="300px" id="grafico2"></canvas>
                </x-adminlte-card>
            </div>
        </div> --}}
    </div>
@stop

@push('js')
    <script>
        $(function() {
            let donutChartCanvas = $('#grafico1').get(0).getContext('2d')
            let donutData = {
                labels: [
                    'Informática',
                    'Edificações',
                    'Logística',
                    'Nutrição & Dietética',
                    'Administração',
                    'Agronegócios',
                ],
                datasets: [{
                    label: 'Nº de alunos',
                    data: [700, 500, 400, 600, 300, 100],
                }]
            }
            let donutOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            new Chart(donutChartCanvas, {
                type: 'doughnut',
                data: donutData,
                options: donutOptions
            })

            // --------------- 

            let canvas = $('#grafico2').get(0).getContext('2d')
            let canvasData = {
                labels: [
                    'Informática',
                    'Edificações',
                    'Logística',
                    'Nutrição & Dietética',
                    'Administração',
                    'Agronegócios',
                ],
                datasets: [{
                    label: 'Nº De alunos inscritos',
                    data: [700, 500, 400, 600, 300, 100],
                }]
            }
            let canvasOpt = {
                maintainAspectRatio: false,
                responsive: true,
                datasetFill: false
            }
            new Chart(canvas, {
                type: 'bar',
                data: canvasData,
                options: canvasOpt
            })
        })
    </script>
@endpush
