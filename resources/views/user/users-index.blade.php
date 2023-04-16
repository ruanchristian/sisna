@extends('adminlte::page')

@section('title', 'Lista de Usuários - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Listagem dos Usuários</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover collapsed" id="users-table">
                    <thead>
                        <tr>
                            <th>Índice</th>
                            <th>Nome</th>
                            <th class="no-orderable">Email</th>
                            <th class="no-orderable">Cargo</th>
                            @can('isAdmin', Auth::user())
                                <th class="no-orderable">Ações</th>
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
                                        @if ($user->id === Auth::user()->id)
                                            <button disabled class="btn btn-xs btn-default text-danger mx-1 shadow">
                                                <i class="fas fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-xs btn-default text-danger mx-1 shadow delete-user"
                                                title="Deletar {{ $user->name }}" value="{{ $user->id }}">
                                                <i class="fas fa-lg fa-fw fa-trash"></i>
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

    {{-- Modal de Edição --}}
    <form id="form-update" role="form" action="{{ route('user.update', rand(1, 100)) }}" method="POST">
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
                <x-adminlte-button icon="fas fa-floppy-disk" type="submit" theme="primary" label="Salvar dados" />
            </x-slot>
        </x-adminlte-modal>
    </form>
@stop

@push('js')
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>
    <script src="{{ asset('js/users-management.js') }}"></script>

    <script>
        $(() => {
            $('#users-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                    searchPlaceholder: "Buscar usuário...",
                    info: "Mostrando de _START_ até _END_ de _TOTAL_ usuários",
                    lengthMenu: "Exibir _MENU_ usuários por página"
                },
                columnDefs: [{orderable: false, targets: 'no-orderable'}],
            });
        });
    </script>

    @if (session()->has('error_msg'))
        <x-adminlte-alert theme="danger" title="Erro" dismissable>
            {{ session('error_msg') }}
        </x-adminlte-alert>
    @endif

    @if (session()->has('success'))
        <script>
            Swal.fire(
                'Feito!',
                `{!! session('success') !!}`,
                'success'
            );
        </script>
    @endif
@endpush
