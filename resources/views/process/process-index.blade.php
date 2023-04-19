@extends('adminlte::page')

@section('title', 'Processos Seletivos - ')

@section('plugins.Select2', true)

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Criar/editar processos seletivos</h1>
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
                            id="cursos-select" name="cursos[]" label="Escolha os cursos a serem ofertados:" multiple>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </x-slot>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                            @endforeach
                        </x-adminlte-select2>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="d-flex ml-auto" type="submit" theme="primary" label="Criar" />
                        </x-slot>
                </x-adminlte-card>
                </form>
            </div>

            <div class="col-md mb-3">
                <x-adminlte-card title="Processos seletivos" theme="primary" icon="fas fa-file-pen">

                    @if (!$processos->count())
                        <b class="text-danger">Não existem processos seletivos cadastrados no sistema.</b>
                    @else
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ano</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($processos as $processo)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $processo->ano }}</td>
                                        <td>
                                            @if ($processo->estado == 1)
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input"
                                                        onchange="changeState(this, `{{ $processo->ano }}`)"
                                                        id="{{ $processo->id }}" value="{{ $processo->id }}" checked>
                                                    <label id="{{ $processo->ano }}" class="custom-control-label"
                                                        for="{{ $processo->id }}">Aberto</label>
                                                </div>
                                            @else
                                                <div
                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input"
                                                        onchange="changeState(this, `{{ $processo->ano }}`)"
                                                        id="{{ $processo->id }}" value="{{ $processo->id }}">
                                                    <label id="{{ $processo->ano }}" class="custom-control-label"
                                                        for="{{ $processo->id }}"
                                                        title="Reabrir processo seletivo">Fechado</label>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </x-adminlte-card>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('vendor/custom-input/custom-file-input.js') }}"></script>
    <script src="{{ asset('js/config-processes.js') }}"></script>
@endpush
