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
                <h3 class="card-title">Informe os dados abaixo</h3>
            </div>

            <form role="form" action="{{ route('user.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="text">Nome</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="Nome completo..." required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">Grupo Pertencente</label>

                        <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                            <option value="">Pública Ampla Concorrência</option>
                            <option value="">Pública Residente Próximo</option>
                            <option value="">Particular Ampla Concorrência</option>
                            <option value="">Particular Residente Próximo</option>
                            <option value="">PCD</option>
                        </select>

                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Data de Nascimento</label>
                        <input type="date" name="data" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="DD/MM/AAAA" required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">Opção de Curso</label>

                        <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                            <option value="">Administração</option>
                            <option value="">Agronegócio</option>
                            <option value="">Edificações</option>
                            <option value="">Informática</option>
                            <option value="">Nutrição e Dietética</option>
                            <option value="">Logística</option>
                        </select>

                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Nota de Português</label>
                        <input type="number" name="NP" class="form-control @error('name') is-invalid @enderror"
                            id="nota_pt" placeholder="0.00" required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Nota de Matemática</label>
                        <input type="number" name="NM" class="form-control @error('name') is-invalid @enderror"
                            id="nota_mt" placeholder="0.00" required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Média de Notas</label>
                        <input type="number" name="media_Total" class="form-control @error('name') is-invalid @enderror"
                            id="media_total" placeholder="0.00" required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="text">Processo Seletivo</label>
                        <input type="number" name="ano" class="form-control @error('name') is-invalid @enderror"
                            id="ano" min="{{ date('Y') + 1 }}" required value={{ old('name') }}>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div>

                <div class="card-footer">
                    <x-adminlte-button type="submit" class="d-flex ml-auto" label="Cadastrar" theme="primary" />
                </div>
            </form>
        </div>

    </div>
@stop