@extends('adminlte::page')

@section('title', 'Changelogs - ')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Atualizações</h1>
            </div>
            <div class="col-sm-6 text-sm-right">
                @can('isDev', Auth::user())
                    <a href="{{ route('changelog.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nova versão
                    </a>
                @endcan
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <x-adminlte-alert theme="success" title="Sucesso" dismissable>
                {{ session('success') }}
            </x-adminlte-alert>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histórico de versões</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($versoes as $v)
                    @php
                        $qtd = collect($v->mudancas)->flatten()->count();
                    @endphp
                    <div class="list-group-item d-flex align-items-center p-0">
                        <a href="{{ route('changelog.show', $v) }}"
                            class="flex-grow-1 d-flex align-items-center text-dark p-3">
                            <i class="fas fa-code-branch text-muted mr-3"></i>
                            <div class="flex-grow-1">
                                <b>v{{ $v->versao }}</b>
                                @if ($v->id === $atualId)
                                    <span class="badge badge-success ml-1">Versão atual</span>
                                @endif
                                @if ($novas->contains($v->id))
                                    <span class="badge badge-novo ml-1">Novo</span>
                                @endif
                                <br>
                                <small class="text-muted">
                                    {{ $v->data->format('d/m/Y') }} · {{ $qtd }}
                                    {{ $qtd == 1 ? 'alteração' : 'alterações' }}
                                </small>
                            </div>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>

                        @can('isDev', Auth::user())
                            <div class="px-2 d-flex">
                                <a href="{{ route('changelog.edit', $v) }}" class="btn btn-sm btn-default mr-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('changelog.destroy', $v) }}" method="POST"
                                    onsubmit="return confirm('Excluir a versão v{{ $v->versao }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-default" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                @empty
                    <div class="list-group-item text-muted">Nenhuma versão registrada.</div>
                @endforelse
            </div>
            <div class="d-flex justify-content-center">
                {{ $versoes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@stop
