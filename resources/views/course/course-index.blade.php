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
                <form action="{{ route('course.store') }}" method="POST">
                    @csrf
                    <x-adminlte-card title="Criar cursos" theme="primary" icon="fas fa-book-open">
                        <x-adminlte-input minlength='3' maxlength='20' type="text" name="nome" label="Informe o nome do curso:"
                            placeholder="Informe o nome do curso aqui..." required>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-book-open-reader"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>

                        <label>Escolha a cor do curso:</label>
                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-primary @error('cor_curso') is-invalid @enderror"
                                type="radio" id="azul" name="cor_curso" value="#007bff" required>
                            <label for="azul" class="custom-control-label">Azul</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-danger @error('cor_curso') is-invalid @enderror"
                                type="radio" id="vermelho" name="cor_curso" value="#dc3545">
                            <label for="vermelho" class="custom-control-label">Vermelho</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-roxo @error('cor_curso') is-invalid @enderror"
                                type="radio" id="roxo" name="cor_curso" value="#9400d3">
                            <label for="roxo" class="custom-control-label">Roxo</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-success @error('cor_curso') is-invalid @enderror"
                                type="radio" id="verde" name="cor_curso" value="#008000">
                            <label for="verde" class="custom-control-label">Verde</label>
                        </div>

                        <div class="custom-control custom-radio">
                            <input
                                class="custom-control-input custom-control-input-warning @error('cor_curso') is-invalid @enderror"
                                type="radio" id="amarelo" name="cor_curso" value="#ffc107">
                            <label for="amarelo" class="custom-control-label">Amarelo</label>
                            @error('cor_curso')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <x-slot name="footerSlot">
                            <x-adminlte-button class="d-flex ml-auto" type="submit" theme="primary" label="Adicionar" />
                        </x-slot>
                    </x-adminlte-card>
                </form>
            </div>

            <div class="col mb-3">
                <x-adminlte-card title="Editar cursos" theme="primary" icon="fas fa-pen">
                    @if ($courses->isEmpty())
                        <b class="text-danger">Não existem processos seletivos cadastrados no sistema.</b>
                    @else
                      <div class="table-responsive"> 
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Índice</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <span
                                                style="color: @if ($course->cor_curso == '#ffc107') #000; @else #FFF; @endif background-color: {{ $course->cor_curso }}"
                                                class="badge translate-middle p-2">
                                                {{ $course->nome }}
                                            </span>
                                        </td>
                                        <td>
                                            <button title="Editar {{ $course->nome }}" class="btn btn-xs btn-default text-primary mx-1 shadow edit-course"
                                                value="{{ $course->id }}">
                                                <i class="fas fa-lg fa-fw fa-pen"></i>
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
        </div>
    </div>

    <form id="form-update" role="form" action="{{ route('course.update', rand(1, 100)) }}" method="POST">
        @method('PUT')
        @csrf
        <x-adminlte-modal id="modal-course" title="Editar curso" icon="fas fa-pen" static-backdrop scrollable>

            <x-adminlte-input minlength="3" maxlength="20" name="nome" id="name-course" label="Nome do curso:" placeholder="Escolha um nome para o curso...">
                <x-slot name="prependSlot">
                    <div class="input-group-text">
                        <i class="fas fa-graduation-cap"></i>
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
    <script src="{{ asset('js/config-course.js') }}"></script>

    @if (session()->has('message'))
        <script>
            Swal.fire({
                title: 'Curso cadastrado!',
                html: `{!! session('message') !!}`,
                icon: 'success',
                confirmButtonColor: '#3c6cac'
            });
        </script>
    @endif

    @if (session()->has('success'))
        <script>
            Swal.fire({
                title: 'Feito!',
                html: `{!! session('success') !!}`,
                icon: 'success',
                confirmButtonColor: '#3c6cac'
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Swal.fire({
                title: 'ERRO!!',
                text: `{{ session('error') }}`,
                icon: 'error',
                confirmButtonColor: '#3c6cac'
            });
        </script>
    @endif
@endpush
