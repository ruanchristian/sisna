@extends('adminlte::page')

@section('title', 'v' . $changelog->versao . ' - ')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Versão v{{ $changelog->versao }}</h1>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="{{ route('changelog.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar às novidades
                </a>
                @can('isDev', Auth::user())
                    <a href="{{ route('changelog.edit', $changelog) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('content')
    @php
        $tipos = [
            'adicionado' => ['label' => 'Novo',     'cor' => 'success'],
            'melhorado'  => ['label' => 'Melhoria', 'cor' => 'info'],
            'corrigido'  => ['label' => 'Correção', 'cor' => 'danger'],
            'removido'   => ['label' => 'Removido', 'cor' => 'secondary'],
        ];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>v{{ $changelog->versao }}</b>
                    <small class="ml-2">Lançada em {{ $changelog->data->format('d/m/Y') }}</small>
                </h3>
            </div>
            <div class="card-body">
                @foreach ($tipos as $chave => $tipo)
                    @if (!empty($changelog->mudancas[$chave]))
                        <p class="mb-1 mt-2">
                            <span class="badge badge-{{ $tipo['cor'] }}">{{ $tipo['label'] }}</span>
                        </p>
                        <ul class="mb-2">
                            @foreach ($changelog->mudancas[$chave] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@stop