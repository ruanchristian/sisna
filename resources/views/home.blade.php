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
                        <h3>{{ $processes_count }}</h3>
                        <p>Processos seletivos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrows-down-to-people"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $courses_count }}</h3>
                        <p>Cursos EEEPJAS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>2</h3>
                        <p>...</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>0</h3>
                        <p>.,,.</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-code"></i>
                    </div>
                </div>
            </div>
        </div>

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
        </div>
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
