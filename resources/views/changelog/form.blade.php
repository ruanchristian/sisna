@extends('adminlte::page')

@php $editando = isset($changelog); @endphp

@section('title', ($editando ? 'Editar versão' : 'Nova versão') . ' - ')

@section('content_header')
    <div class="container-fluid">
        <h1>{{ $editando ? 'Editar versão v' . $changelog->versao : 'Nova versão' }}</h1>
    </div>
@stop

@section('content')
    @php
        $campos = [
            'adicionado' => 'Novo (adicionado)',
            'melhorado'  => 'Melhorias',
            'corrigido'  => 'Correções',
            'removido'   => 'Removido',
        ];
    @endphp

    <div class="container-fluid">
        <form method="POST"
            action="{{ $editando ? route('changelog.update', $changelog) : route('changelog.store') }}">
            @csrf
            @if ($editando)
                @method('PUT')
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="versao">Versão</label>
                                <input type="text" id="versao" name="versao" placeholder="1.2.0"
                                    class="form-control @error('versao') is-invalid @enderror"
                                    value="{{ old('versao', $changelog->versao ?? '') }}" required>
                                @error('versao')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="data">Data de lançamento</label>
                                <input type="date" id="data" name="data"
                                    class="form-control @error('data') is-invalid @enderror"
                                    value="{{ old('data', isset($changelog) ? $changelog->data->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                    required>
                                @error('data')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mb-2">Escreva <b>um item por linha</b>. Deixe em branco o que não se aplica.</p>
                    @error('mudancas')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    @foreach ($campos as $chave => $rotulo)
                        <div class="form-group">
                            <label for="mud-{{ $chave }}">{{ $rotulo }}</label>
                            <textarea id="mud-{{ $chave }}" name="mudancas[{{ $chave }}]" rows="3"
                                class="form-control">{{ old("mudancas.$chave", $editando ? implode("\n", $changelog->mudancas[$chave] ?? []) : '') }}</textarea>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                    <a href="{{ route('changelog.index') }}" class="btn btn-default">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
@stop