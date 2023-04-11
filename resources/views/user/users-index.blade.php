@extends('adminlte::page')

@section('title', 'Lista de Usuários - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Listagem dos Usuários</h1>
    </div>
@stop

@section('content')
    @if (session()->has('error_msg'))
        <x-adminlte-alert theme="danger" title="Erro" dismissable>
            {{ session('error_msg') }}
        </x-adminlte-alert>
    @endif

    @if (session()->has('success'))
        <x-adminlte-alert theme="success" title="Feito" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover collapsed" id="users-table">
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
                                        <button title="Editar {{ $user->name }}"
                                            class="btn btn-xs btn-default text-primary mx-1 shadow edit-user"
                                            value="{{ $user->id }}">
                                            <i class="fas fa-lg fa-fw fa-pen"></i>
                                        </button>
                                        <form class="d-inline-block" action="{{ route('user.destroy', $user->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf

                                            @if ($user->id === Auth::user()->id)
                                                <button disabled class="btn btn-xs btn-default text-danger mx-1 shadow">
                                                    <i class="fas fa-lg fa-fw fa-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                    title="Deletar {{ $user->name }}">
                                                    <i class="fas fa-lg fa-fw fa-trash"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal de Edição --}}
    <form id="form-update" role="form" action="{{ route('user.update', 0) }}" method="POST">
        @method('PUT')
        @csrf
        <x-adminlte-modal id="modalEdit" title="Editar usuário" icon="fas fa-user-pen" static-backdrop scrollable>

            <x-adminlte-input name="name" id="name-user" label="Nome" placeholder="Nome completo">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-input type="email" name="email" id="email" label="Email" placeholder="Email">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-adminlte-select name="type" id="type-user" label="Cargo do usuário">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </x-slot>

                <option value="administrador">Administrador</option>
                <option value="monitor">Monitor</option>
            </x-adminlte-select>

            <x-adminlte-input type="password" name="password" label="Senha" placeholder="Senha">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>

            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" theme="info" label="Salvar dados" />
            </x-slot>
        </x-adminlte-modal>
    </form>
@stop

@push('js')
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/users-management.js') }}"></script>
@endpush
