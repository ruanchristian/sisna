@extends('adminlte::page')

@section('title', "Participantes de $process->ano - ")

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="mb-0">Visualizar/editar novos alunos</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('student.index', $process->id) }}">Cadastrar alunos no processo de {{ $process->ano }}</a>
                </li>
                <li class="breadcrumb-item active">
                    Visualização
                </li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="container-fluid">
        <x-adminlte-card>
            @if ($students->isEmpty())
                <span class="text-danger">Não existem alunos cadastrados no processo de {{ $process->ano }}</span>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover collapsed" id="students-table">
                        <thead>
                            <tr>
                                <th class="no-orderable">ID</th>
                                <th class="no-orderable">Nome</th>
                                <th class="no-orderable">Curso</th>
                                <th>Data de Nasc.</th>
                                <th>Média PT</th>
                                <th>Média MT</th>
                                <th>Média Final</th>
                                <th class="no-orderable">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->nome }}</td>
                                    <td>{{ $student->course->nome }}</td>
                                    <td>{{ date('d/m/Y', strtotime($student->data_nascimento)) }}</td>
                                    <td>{{ $student->media_pt }}</td>
                                    <td>{{ $student->media_mt }}</td>
                                    <td>{{ $student->media_final }}</td>
                                    <td>
                                        <a href="{{ route('student.edit', [$process->id, $student->id]) }}">
                                            <button title="Editar {{ $student->nome }}"
                                                class="btn btn-xs btn-default text-primary mx-1 mb-1 shadow">
                                                <i class="fas fa-lg fa-fw fa-pen"></i>
                                            </button></a>

                                        <button title="Deletar {{ $student->nome }}"
                                            class="btn btn-xs btn-default text-danger mx-1 mb-1 shadow">
                                            <i class="fas fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-adminlte-card>
    </div>
@stop

@push('js')
    <script src="https://cdn.datatables.net/v/bs4/dt-1.13.4/datatables.min.js"></script>

    <script>
        $(() => {
            $('#students-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                    searchPlaceholder: "Buscar aluno...",
                    info: "Mostrando de _START_ até _END_ de _TOTAL_ alunos",
                    lengthMenu: "Exibir _MENU_ alunos por página"
                },
                columnDefs: [{
                    orderable: false,
                    targets: 'no-orderable'
                }],
            });
        });
    </script>
@endpush
