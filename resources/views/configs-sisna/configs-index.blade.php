@extends('adminlte::page')

@section('title', 'Configurar critérios - ')

@section('content_header')
    <div class="container-fluid">
        <h1>Ajustar critérios de seleção - <u>{{ $process->ano }}</u></h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if($errors->any())
        <x-adminlte-alert theme="danger" title="Erro" dismissable>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

        <form id="configs" action="{{ route('configs.update', $process) }}" method="POST">
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

            <x-adminlte-card theme="primary" title="Redistribuição de vagas remanescentes" icon="fas fa-project-diagram">
                <p class="text-muted mb-3">
                    Para cada categoria, informe <b>em ordem</b> para onde vão as vagas que sobrarem quando não houver
                    candidatos suficientes. <b>Classificação geral (classificáveis)</b> significa qualquer candidato ainda não aprovado,
                    pela ordem de classificação do curso.
                </p>
                <div class="row">
                    <div class="col-lg-6" id="redis-editor"></div>
                    <div class="col-lg-6 d-flex flex-column">
                        <div class="border rounded p-2 flex-grow-1 d-flex align-items-center justify-content-center"
                            style="min-height: 280px; overflow-x: auto;">
                            <div id="redis-diagram" class="w-100 text-center text-muted">Carregando diagrama...</div>
                        </div>

                        {{-- Legenda do diagrama --}}
                        <div class="small text-muted mt-2 redis-legend">
                            <div><span class="redis-line"></span> <b>1ª opção</b>: primeiro destino das vagas que sobrarem da categoria.</div>
                            <div><span class="redis-line dashed"></span> <b>2ª, 3ª... opção</b>: só é usada se a opção anterior não tiver mais candidatos.</div>
                            <div><span class="redis-node geral"></span> <b>Classificação geral</b>: qualquer candidato ainda não aprovado, pela ordem de classificação do curso.</div>
                            <div><span class="redis-node vazio"></span> Categoria <b>sem destino</b>: as vagas que sobrarem ficarão vazias.</div>
                            <div class="mt-1">O número dentro de cada categoria é a quantidade de vagas ofertadas (campos acima).</div>
                        </div>
                    </div>
                </div>

                {{-- Leitura do grafo por extenso --}}
                <div class="mt-3">
                    <h6 class="font-weight-bold mb-2"><i class="fas fa-book-reader"></i> Como as vagas serão redistribuídas</h6>
                    <ul id="redis-summary" class="mb-0 pl-4"></ul>
                </div>
                <div id="redis-warnings" class="mt-3"></div>
                <x-slot name="footerSlot">
                    <button type="button" class="btn btn-sm btn-default" id="redis-reset">
                        <i class="fas fa-undo"></i> Restaurar regra padrão
                    </button>
                </x-slot>
            </x-adminlte-card>

            <input type="hidden" id="order-selection" name="ordem_desempate" value="{{ $process->config->ordem_desempate }}">
            <input type="hidden" id="redistribuicao" name="redistribuicao"
                value="{{ old('redistribuicao', json_encode($redistribuicao['edges'])) }}">

            <x-adminlte-button data-toggle="modal" data-target="#modal-password" class="d-flex ml-auto" label="Salvar" theme="primary" /><br>
        </form>
    </div>

    <x-adminlte-modal id="modal-password" title="Confirme sua senha" icon="fas fa-user-lock">
        <x-adminlte-input type="password" id="pass" label="Senha" placeholder="Sua senha aqui" name="">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-lock"></i>
                </div>
            </x-slot>
        </x-adminlte-input>

        <x-slot name="footerSlot">
            <x-adminlte-button id="submit-modal" theme="primary" label="Salvar"/>
        </x-slot>   
    </x-adminlte-modal>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

    <script src="{{ asset('js/ordering-selectionOrder.js') }}"></script>
    <style>
        .redis-legend > div { margin-bottom: 2px; }
        .redis-line { display: inline-block; width: 28px; vertical-align: middle; border-top: 2px solid #555; }
        .redis-line.dashed { border-top-style: dashed; }
        .redis-node { display: inline-block; width: 14px; height: 14px; vertical-align: middle; border-radius: 3px; }
        .redis-node.geral { background: #e8f0fb; border: 2px solid #3c6cac; }
        .redis-node.vazio { background: #fff4e5; border: 2px solid #e8a33d; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/mermaid@10.9.1/dist/mermaid.min.js"></script>
    <script>window.REDISTRIBUICAO = @json($redistribuicao);</script>
    <script src="{{ asset('js/redistribution-graph.js') }}"></script>

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
