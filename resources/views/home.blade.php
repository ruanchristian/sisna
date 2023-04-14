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
                        <h3>{{ $users->count() }}</h3>
                        <p>Usuários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $processes->count() }}</h3>
                        <p>Processos seletivos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrows-down-to-people"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $courses->count() }}</h3>
                        <p>Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $admin_count }}</h3>
                        <p>Admins</p>
                    </div>
                    <div class="icon">
                       <i class="fas fa-code"></i>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="row mt-5">
           <div class="col-lg-3 col-6">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h2>Gráficos</h2>
                </div>
                <div class="card-body">

                </div>
           </div>
            </div> 
        </div> --}}
    </div>
@stop
