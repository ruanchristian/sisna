@extends('adminlte::page')

@section('title', 'Cursos - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Criar/editar cursos</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid">
        <div class="row mt-1">
            <div class="col mb-3">
                <x-adminlte-card title="Criar cursos" theme="primary" icon="fas fa-book-open">
                    <x-adminlte-input type="text" name="nome" label="Informe o nome do curso:"
                        placeholder="Informe o nome do curso aqui..." required>
                        <x-slot name="prependSlot">
                            <div class="input-group-text">
                                <i class="fas fa-book-open-reader"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>

                    <x-adminlte-select id="optionsLangs" name="optionsLangs[]" label="Escolha a cor:" label-class="text-danger">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-gradient-red">
                                <i class="fas fa-lg fa-language"></i>
                            </div>
                        </x-slot>
                        <option class="bg-primary" value="">jhbhj</option>
                        <option style="color: #F39C12" value="">jhbhj</option>
                        <option style="color: #F39C12" value="">jhbhj</option>
                        <option style="color: #F39C12" value="">jhbhj</option>
                    </x-adminlte-select>


                    <x-slot name="footerSlot">
                        <x-adminlte-button class="d-flex ml-auto" type="submit" theme="primary" label="Adicionar" />
                    </x-slot>
                </x-adminlte-card>
            </div>

            <div class="col mb-3">
                <x-adminlte-card title="Editar cursos" theme="primary" icon="fas fa-pen">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>Informática</td>
                                <td>
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow edit-user">
                                        <i class="fas fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow delete-user">
                                        <i class="fas fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Edificações</td>
                                <td>
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow edit-user">
                                        <i class="fas fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow delete-user">
                                        <i class="fas fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Logística</td>
                                <td>
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow edit-user">
                                        <i class="fas fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow delete-user">
                                        <i class="fas fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- @foreach ($processos as $processo)
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
                            @endforeach --}}
                        </tbody>
                    </table>
                </x-adminlte-card>
            </div>
        </div>
    </div>

@stop
