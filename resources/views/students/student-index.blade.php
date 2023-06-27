@extends('adminlte::page')

@section('title', 'Cadastrar participantes - ')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="mb-0">{{ !isset($student) ? 'Cadastro de novos alunos' : 'Editar aluno' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.visualization', $process->id) }}">Visualizar novos alunos {{ $process->ano }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ !isset($student) ? 'Modo de cadastro' : 'Modo de edição' }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if (isset($student))
            <form action="{{ route('student.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
            @else
            <form action="{{ route('student.create', $process->id) }}" method="POST">
                @csrf
        @endif
        <x-adminlte-card theme="primary"
            title="{{ isset($student) ? 'Edite os dados do aluno abaixo' : 'Informe os dados abaixo' }}"
            icon="fas fa-user-graduate">
            <x-adminlte-input label="Nome completo" name="nome" type="text" placeholder="Nome completo..."
                value="{{ isset($student) ? $student->nome : old('nome') }}" required />

            <x-adminlte-select name="origem" label="Grupo pertencente">
                <option @if (isset($student) && $student->origem === 'PUBLICA-AMPLA') selected @endif value="PUBLICA-AMPLA">Pública Ampla Concorrência
                </option>
                <option @if (isset($student) && $student->origem === 'PUBLICA-PROX-EEEP') selected @endif value="PUBLICA-PROX-EEEP">Pública Residente
                    Próximo</option>
                <option @if (isset($student) && $student->origem === 'PRIVATE-AMPLA') selected @endif value="PRIVATE-AMPLA">Particular Ampla
                    Concorrência</option>
                <option @if (isset($student) && $student->origem === 'PRIVATE-PROX-EEEP') selected @endif value="PRIVATE-PROX-EEEP">Particular Residente
                    Próximo</option>
                <option @if (isset($student) && $student->origem === 'PCD') selected @endif value="PCD">PCD</option>
            </x-adminlte-select>

            <x-adminlte-input label="Data de Nascimento" name="data_nascimento" type="date"
                value="{{ isset($student) ? $student->data_nascimento : old('data_nascimento') }}" required />

            <x-adminlte-select name="curso_id" label="Opção de curso">
                @forelse ($courses as $course)
                    <option @if (isset($student) && $student->course->id === $course->id) selected @endif value="{{ $course->id }}">
                        {{ $course->nome }}</option>
                @empty
                    <option selected disabled>Erro ao carregar os cursos desse processo.</option>
                @endforelse
            </x-adminlte-select>

            <x-adminlte-input min="0" max="10" label="Média de Português" name="media_pt" type="number"
                step="0.01" value="{{ isset($student) ? $student->media_pt : old('media_pt') }}" required />
            <x-adminlte-input min="0" max="10" label="Média de Matemática" name="media_mt" type="number"
                step="0.01" value="{{ isset($student) ? $student->media_mt : old('media_mt') }}" required />
            <x-adminlte-input min="0" max="10" label="Média Final" name="media_final" type="number"
                step="0.01" value="{{ isset($student) ? $student->media_final : old('media_final') }}" required />

            <x-adminlte-select disabled name="" label="Processo seletivo">
                <option selected value="{{ $process->id }}">{{ $process->ano }}</option>
            </x-adminlte-select>

            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="d-flex ml-auto"
                    label=" {{ isset($student) ? 'Salvar' : 'Cadastrar' }}" theme="primary" />
            </x-slot>
        </x-adminlte-card>
        </form>
    </div>
@stop

@push('js')
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
@endpush
