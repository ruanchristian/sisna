@extends('adminlte::page')

@section('title', 'Alunos - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Cadastro de Novos Estudantes</h1>
    </div>
@stop

@section('content')

    <div class="container-fluid">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate"></i>
                    Insira os dados do participante
                </h3>
            </div>

            <form role="form" action="{{ route('student.create', $process->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <x-adminlte-input label="Nome completo do participante" name="nome" type="text" placeholder="Nome completo..." value="{{ old('nome') }}" required />

                    <x-adminlte-select name="origem" label="Grupo pertencente">
                        <option value="PUBLICA-AMPLA">Pública Ampla Concorrência</option>
                        <option value="PUBLICA-PROX-EEEP">Pública Residente Próximo</option>
                        <option value="PRIVATE-AMPLA">Particular Ampla Concorrência</option>
                        <option value="PRIVATE-PROX-EEEP">Particular Residente Próximo</option>
                        <option value="PCD">PCD</option>
                    </x-adminlte-select>

                    <x-adminlte-input label="Data de Nascimento" name="data_nascimento" type="date" value="{{ old('data_nascimento') }}" required />

                    <x-adminlte-select name="curso_id" label="Opção de curso">
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->nome }}</option>    
                        @endforeach
                    </x-adminlte-select>

                    <x-adminlte-input min="0" max="10" label="Média de Português" name="media_pt"
                        type="number" step="0.01" value="{{ old('media_pt') }}" required />
                    <x-adminlte-input min="0" max="10" label="Média de Matemática" name="media_mt"
                        type="number" step="0.01" value="{{ old('media_mt') }}" required />
                    <x-adminlte-input min="0" max="10" label="Média Final" name="media_final" type="number"
                        step="0.01" value="{{ old('media_final') }}" required />

                    <x-adminlte-select disabled name="processo_id" label="Processo seletivo">
                        <option selected value="{{ $process->id }}">{{ $process->ano }}</option>
                    </x-adminlte-select>
                </div>

                <div class="card-footer">
                    <x-adminlte-button type="submit" class="d-flex ml-auto" label="Cadastrar" theme="primary" />
                </div>
            </form>
        </div>
    </div>
@stop
