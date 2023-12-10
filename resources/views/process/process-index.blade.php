@extends('adminlte::page')

@section('title', 'Processos Seletivos - ')

@section('plugins.Select2', true)

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Criar/visualizar processos seletivos</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col mb-3">
                <x-adminlte-card title="Criar processos" theme="primary" icon="fas fa-calendar-plus">
                    <form action="{{ route('process.store') }}" method="POST">
                        @csrf
                        <x-adminlte-input type="number" min="{{ date('Y') + 1 }}" name="ano"
                            label="Ano do processo seletivo:" placeholder="Informe o ano do processo a ser criado..."
                            required>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>

                        {{-- Select para os cursos --}}

                        <x-adminlte-select2 data-maximum-selection-length="4" data-placeholder="Selecione 4 cursos..."
                            id="cursos-select" name="cursos[]" label="Escolha os cursos a serem ofertados:" multiple
                            required>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </x-slot>
                            @forelse ($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                            @empty
                                <option>Não há cursos cadastrados. Cadastre os cursos</option>
                            @endforelse
                        </x-adminlte-select2>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="d-flex ml-auto" type="submit" theme="primary" label="Criar" />
                        </x-slot>
                </x-adminlte-card>
                </form>
            </div>

            <div class="col-md mb-3">
                <x-adminlte-card title="Processos seletivos" theme="primary" icon="fas fa-file-pen">

                    @if ($processos->isEmpty())
                        <b class="text-danger">Não existem processos seletivos cadastrados no sistema.</b>
                    @else
                        <div style="max-height: 222px;" class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ano</th>
                                        <th>Situação</th>
                                        <th>Participantes</th>
                                        <th>Configurações</th>
                                        <th>Resultados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($processos as $processo)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $processo->ano }}</td>
                                            <td>
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input"
                                                        onchange="changeState(this, `{{ $processo->ano }}`, `{{ $processo->id }}`)"
                                                        id="{{ $processo->id }}" value="{{ $processo->id }}"
                                                        {{ $processo->estado == 1 ? 'checked' : '' }}>
                                                    <label id="{{ $processo->ano }}" class="custom-control-label"
                                                        for="{{ $processo->id }}">
                                                        {{ $processo->estado == 1 ? 'Em andamento' : 'Encerrado' }}
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button {{ $processo->estado == 0 ? 'disabled' : '' }}
                                                        class="btn btn-success dropdown-toggle drop-d{{ $processo->ano }}" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-user-graduate"></i>
                                                        Participantes
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a target="_blank" class="dropdown-item"
                                                                href="{{ route('student.index', $processo->id) }}">Cadastrar
                                                                participantes</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('student.visualization', $processo->id) }}">Ver
                                                                participantes</a></li>
                                                        <li><a class="dropdown-item"
                                                                    href="#">Conferência dos lotes</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('configs.index', $processo->id) }}">
                                                    <button {{ $processo->estado == 0 ? 'disabled' : '' }}
                                                        class="btn btn-info drop-d{{ $processo->ano }}" style="white-space: nowrap;">
                                                        <i class="fas fa-gears"></i>
                                                        Ajustar critérios
                                                    </button>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="res-{{ $processo->ano }}"
                                                     href="{{ $processo->estado ? '#' : route('resultado.index', $processo->id) }}">
                                                    <button {{ $processo->estado ? 'disabled' : '' }} id="ver-{{ $processo->ano }}"
                                                        title="Ver resultados de {{ $processo->ano }}"
                                                        class="btn btn-xs btn-default text-primary mx-1 shadow ml-4 mt-1">
                                                        <i class="fas fa-xl fa-eye"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-adminlte-card>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('vendor/custom-input/custom-file-input.js') }}"></script>
    <script src="{{ asset('js/config-processes.js') }}"></script>

    @if (session()->has('error_msg'))
        <script>
            Swal.fire({
                title: 'ERRO!!',
                text: `{{ session('error_msg') }}`,
                icon: 'error',
                confirmButtonColor: '#3c6cac'
            });
        </script>
    @endif
@endpush
