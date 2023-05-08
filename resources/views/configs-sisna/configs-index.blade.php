@extends('adminlte::page')

@section('title', 'Configurar critérios - ')

@section('content_header')
    <div class="container-fluid">
        <h1>Ajustar critérios de seleção - <span style="text-decoration: underline;">{{ $process->ano }}</span></h1>
    </div>
@stop

@section('content')

    <div class="container-fluid">
        @if($errors->any())
         <x-adminlte-alert theme="danger" title="Erro" dismissable>
            {{ $errors->first('msg') }}
        </x-adminlte-alert>
        @endif

        <x-adminlte-card theme="primary" title="Ordenar critérios de classificação" icon="fas fa-sort-amount-up">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Posição</th>
                            <th>Critério</th>
                            <th>Ordenação</th>
                        </tr>
                    </thead>
                    <tbody style="cursor: grab" id="sortable">
                        @foreach ($ordem as $criterio => $valor)
                            <tr>
                                <td>{{ $loop->iteration }}º</td>
                                <td>{{ ucwords(str_replace('_', ' ', $criterio)) }}</td>
                                <td>
                                    <x-adminlte-select name="" id="{{ $criterio }}">
                                        @if ($criterio === 'data_nascimento')
                                          <option value="ASC" {{ $valor == 'ASC' ? 'selected' : '' }} >Do mais velho pro mais novo</option>
                                          <option value="DESC" {{ $valor == 'DESC' ? 'selected' : '' }} >Do mais novo pro mais velho</option>

                                         @else
                                          <option value="ASC" {{ $valor == 'ASC' ? 'selected' : '' }} >Da menor nota pra maior nota</option>
                                          <option value="DESC" {{ $valor == 'DESC' ? 'selected' : '' }} >Da maior nota pra menor nota</option>
                                        @endif
                                    </x-adminlte-select>
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        </x-adminlte-card>

        <form action="{{ route('configs.update', $process) }}" method="POST">
            @csrf
            @method('PUT')
            <x-adminlte-card theme="primary" title="Ajustar número de vagas ofertadas" icon="fas fa-cog">
                <x-adminlte-input name="vagas_pcd" min="0" max="45" type="number"
                    label="Quantidade de Vagas PCD (Deficientes)" value="{{ $process->config->vagas_pcd }}" required />
                <x-adminlte-input name="vagas_publica_ampla" min="0" max="45" type="number"
                    label="Quantidade de Vagas Ampla Concorrência (Rede Pública)"
                    value="{{ $process->config->vagas_publica_ampla }}" required />
                <x-adminlte-input name="vagas_publica_prox" min="0" max="45" type="number"
                    label="Quantidade de Vagas dos Alunos Próximos a Escola (Rede Pública)"
                    value="{{ $process->config->vagas_publica_prox }}" required />
                <x-adminlte-input name="vagas_private_ampla" min="0" max="45" type="number"
                    label="Quantidade de Vagas Ampla Concorrência (Rede Privada)"
                    value="{{ $process->config->vagas_private_ampla }}" required />
                <x-adminlte-input name="vagas_private_prox" min="0" max="45" type="number"
                    label="Quantidade de Vagas dos Alunos Próximos a Escola (Rede Privada)"
                    value="{{ $process->config->vagas_private_prox }}" required />
            </x-adminlte-card>

            <input type="hidden" id="order-selection" name="ordem_desempate" value="{{ $process->config->ordem_desempate }}">

            <x-adminlte-button type="submit" class="d-flex ml-auto" label="Salvar" theme="primary" />
        </form>
    </div>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

    <script src="{{ asset('js/ordering-selectionOrder.js') }}"></script>

    @if (session()->has('message'))
        <script>
            Swal.fire({
                title: 'Sucesso!',
                text: `{{ session('message') }}`,
                icon: 'success',
                confirmButtonColor: '#3c6cac'
            });
        </script>
    @endif
@endpush
