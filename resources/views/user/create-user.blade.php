@extends('adminlte::page')

@section('title', 'Cadastro de Usuário - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Cadastro de Usuário</h1>
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
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="email" placeholder="Email" required value={{ old('email') }}>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">Cargo do usuário</label>

                        <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                            <option value="administrador">Administrador</option>
                            <option value="monitor">Monitor</option>
                        </select>

                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password"
                            id="password" placeholder="Senha">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="passwordConfirm">Confirmação da senha</label>
                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                            name="password_confirmation" id="passwordConfirm" placeholder="Insira a senha novamente">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i>
                        Salvar usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
    <script>
        let passConfirmField = document.getElementById('passwordConfirm');

        passConfirmField.addEventListener('paste', (e) => {
            e.preventDefault();
            return false;
        });
    </script>

    @if (session()->has('message'))
        <script>
            Swal.fire(
                'Cadastro concluído!',
                `{!! session('message') !!}`,
                'success'
            );
        </script>
    @endif
@endpush
