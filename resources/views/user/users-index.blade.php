@extends('adminlte::page')

@section('title', 'Lista de Usuários - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Lista dos Usuários</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover dataTable dtr-inline collapsed" id="table">
                    <thead>
                        <tr>
                            <th>Índice</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Cargo</th>
                            @can('isAdmin', Auth::user())
                                <th>Ações</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->type) }}</td>
                                @can('isAdmin', Auth::user())
                                    <td>
                                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </button>
                                        @if ($user->id === Auth::user()->id)
                                            <button disabled class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                title="Delete">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
