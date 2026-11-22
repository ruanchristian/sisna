@extends('adminlte::page')

@section('title', !isset($student) ? 'Cadastrar aluno - ' : 'Editar aluno - ')

@php
    $editing = isset($student);
    $val = fn (string $field, $default = '') => old($field, $editing ? $student->$field : $default);
    $checked = fn (string $field) => old() ? (bool) old($field) : ($editing && $student->$field);
@endphp

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="mb-0">{{ !$editing ? 'Ficha de inscrição' : 'Editar aluno' }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.visualization', $process->id) }}">Visualizar novos alunos {{ $process->ano }}</a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ !$editing ? 'Modo de cadastro' : 'Modo de edição' }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if ($editing)
            <form action="{{ route('student.update', $student->id) }}" method="POST" id="student-form">
                @csrf
                @method('PUT')
        @else
            <form action="{{ route('student.create', $process->id) }}" method="POST" id="student-form">
                @csrf
        @endif

        @if ($errors->any())
            <x-adminlte-alert theme="danger" title="Verifique os campos abaixo" dismissable>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-adminlte-alert>
        @endif

        {{-- Identificação do aluno --}}
        <x-adminlte-card theme="primary" theme-mode="outline" title="Identificação do aluno" icon="fas fa-user-graduate">
            <div class="row">
                <x-adminlte-input fgroup-class="col-md-8" label="Nome completo" name="nome" type="text"
                    placeholder="Nome completo..." value="{{ $val('nome') }}" maxlength="50" required />
                <x-adminlte-input fgroup-class="col-md-4" label="Data de nascimento" name="data_nascimento" type="date"
                    value="{{ $val('data_nascimento') }}" required />
            </div>
            <div class="row">
                <x-adminlte-input fgroup-class="col-md-6" label="Endereço (Rua/Av.)" name="endereco" type="text"
                    value="{{ $val('endereco') }}" maxlength="120" :required="!$editing" />
                <x-adminlte-input fgroup-class="col-md-2" label="Nº" name="numero" type="text"
                    value="{{ $val('numero') }}" maxlength="10" />
                <x-adminlte-input fgroup-class="col-md-4" label="CEP" name="cep" type="text" placeholder="00000-000"
                    value="{{ $val('cep', '63800-000') }}" maxlength="9" data-mask="cep" />
            </div>
            <div class="row">
                <x-adminlte-input fgroup-class="col-md-6" label="Bairro" name="bairro" type="text"
                    value="{{ $val('bairro') }}" maxlength="80" :required="!$editing" />
                <x-adminlte-input fgroup-class="col-md-3" label="Telefone" name="telefone_1" type="text"
                    placeholder="(88) 90000-0000" value="{{ $val('telefone_1') }}" maxlength="20" data-mask="fone"
                    :required="!$editing" />
                <x-adminlte-input fgroup-class="col-md-3" label="Telefone 2 (opcional)" name="telefone_2" type="text"
                    placeholder="(88) 90000-0000" value="{{ $val('telefone_2') }}" maxlength="20" data-mask="fone" />
            </div>
        </x-adminlte-card>

        <x-adminlte-card theme="primary" theme-mode="outline" title="Escola de origem (onde cursou o 9º ano)" icon="fas fa-school">
            <div class="row">
                <div class="form-group col-md-5">
                    <label>Rede de ensino</label>
                    <div>
                        @foreach (['ESTADUAL' => 'Estadual', 'MUNICIPAL' => 'Municipal', 'PARTICULAR' => 'Particular'] as $key => $label)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="tipo_{{ $key }}"
                                    name="escola_origem_tipo" value="{{ $key }}"
                                    @checked($val('escola_origem_tipo') === $key) @required(!$editing)>
                                <label class="custom-control-label" for="tipo_{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('escola_origem_tipo') <span class="text-danger small"><strong>{{ $message }}</strong></span> @enderror
                </div>
                <x-adminlte-input fgroup-class="col-md-7" label="Nome da escola" name="escola_origem_nome" type="text"
                    value="{{ $val('escola_origem_nome') }}" maxlength="120" :required="!$editing" />
            </div>
        </x-adminlte-card>

        <x-adminlte-card theme="primary" theme-mode="outline" title="Opção de curso, categoria e notas" icon="fas fa-clipboard-list">
            <div class="row">
                <x-adminlte-select fgroup-class="col-md-6" name="curso_id" label="Opção de curso">
                    @forelse ($courses as $course)
                        <option @selected((int) $val('curso_id') === $course->id) value="{{ $course->id }}">
                            {{ $course->nome }}</option>
                    @empty
                        <option selected disabled>Erro ao carregar os cursos desse processo.</option>
                    @endforelse
                </x-adminlte-select>

                <x-adminlte-select fgroup-class="col-md-6" name="origem" label="Categoria">
                    @foreach ([
                        'PUBLICA-AMPLA' => 'Pública Ampla Concorrência',
                        'PUBLICA-PROX-EEEP' => 'Pública Residente Próximo (bairro de território)',
                        'PRIVATE-AMPLA' => 'Particular Ampla Concorrência',
                        'PRIVATE-PROX-EEEP' => 'Particular Residente Próximo (bairro de território)',
                        'PCD' => 'PCD (necessidades especiais)',
                    ] as $key => $label)
                        <option @selected($val('origem') === $key) value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="row">
                <x-adminlte-input fgroup-class="col-md-4" min="0" max="10" step="0.01" type="number"
                    label="Média final (6º ao 9º ano)" name="media_final" value="{{ $val('media_final') }}" required />
                <x-adminlte-input fgroup-class="col-md-4" min="0" max="10" step="0.01" type="number"
                    label="Média de Língua Portuguesa" name="media_pt" value="{{ $val('media_pt') }}" required />
                <x-adminlte-input fgroup-class="col-md-4" min="0" max="10" step="0.01" type="number"
                    label="Média de Matemática" name="media_mt" value="{{ $val('media_mt') }}" required />
            </div>
        </x-adminlte-card>

        {{-- Responsável e documentos --}}
        <x-adminlte-card theme="primary" theme-mode="outline" title="Responsável e entrega de documentos" icon="fas fa-file-signature">
            <div class="row">
                <x-adminlte-input fgroup-class="col-md-8" label="Nome do responsável" name="responsavel_nome" type="text"
                    value="{{ $val('responsavel_nome') }}" maxlength="100" :required="!$editing" />
                <x-adminlte-input fgroup-class="col-md-4" label="Parentesco" name="responsavel_parentesco" type="text"
                    list="parentescos" placeholder="Mãe, Pai, Avó..." value="{{ $val('responsavel_parentesco') }}"
                    maxlength="30" :required="!$editing" />
                <datalist id="parentescos">
                    @foreach (['Mãe', 'Pai', 'Avó', 'Avô', 'Tia', 'Tio', 'Irmã', 'Irmão', 'Tutor(a) legal'] as $p)
                        <option value="{{ $p }}">
                    @endforeach
                </datalist>
            </div>

            <label class="d-block">Documentos entregues</label>
            @foreach ([
                'doc_comprovante_idade' => 'Cópia do comprovante de idade (Registro de Nascimento; RG)',
                'doc_comprovante_residencia' => 'Cópia do comprovante de residência',
                'doc_historico' => 'Cópia do histórico ou declaração com notas do 6º ao 9º ano',
            ] as $field => $label)
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="{{ $field }}" name="{{ $field }}" value="1"
                        @checked($checked($field))>
                    <label class="custom-control-label font-weight-normal" for="{{ $field }}">{{ $label }}</label>
                </div>
            @endforeach

            <x-slot name="footerSlot">
                <div class="d-flex align-items-center">
                    <span class="text-muted">Processo seletivo: <b>{{ $process->ano }}</b>
                        @if ($editing && $student->numero_inscricao)
                            &middot; Nº de inscrição: <b>{{ $student->numero_inscricao }}</b>
                        @endif
                    </span>
                    <div class="ml-auto">
                        @if ($editing)
                            <a href="{{ route('student.ficha', $student->id) }}" target="_blank" class="btn btn-default mr-2">
                                <i class="fas fa-print"></i> Imprimir ficha
                            </a>
                        @endif
                        <x-adminlte-button type="submit" label="{{ $editing ? 'Salvar' : 'Cadastrar inscrição' }}"
                            theme="primary" />
                    </div>
                </div>
            </x-slot>
        </x-adminlte-card>
        </form>
    </div>
@stop

@push('js')
    <script>
        // Máscaras simples de CEP e telefone
        const masks = {
            cep: d => d.slice(0, 8).replace(/^(\d{5})(\d)/, '$1-$2'),
            fone: d => {
                d = d.slice(0, 11);
                if (d.length <= 2) return d.length ? `(${d}` : '';
                const mid = d.length === 11 ? 7 : 6;
                return `(${d.slice(0, 2)}) ${d.slice(2, mid)}${d.length > mid ? '-' + d.slice(mid) : ''}`;
            },
        };
        document.querySelectorAll('[data-mask]').forEach(el => {
            el.addEventListener('input', () => el.value = masks[el.dataset.mask](el.value.replace(/\D/g, '')));
        });

        const origem = document.querySelector('select[name="origem"]');
        function syncCategoria() {
            const tipo = document.querySelector('input[name="escola_origem_tipo"]:checked')?.value;
            if (!tipo) return;
            const prefixo = tipo === 'PARTICULAR' ? 'PRIVATE' : 'PUBLICA';
            [...origem.options].forEach(o => {
                o.disabled = o.value !== 'PCD' && !o.value.startsWith(prefixo);
            });
            if (origem.selectedOptions[0]?.disabled) {
                origem.value = origem.value.endsWith('PROX-EEEP') ? `${prefixo}-PROX-EEEP` : `${prefixo}-AMPLA`;
            }
        }
        document.querySelectorAll('input[name="escola_origem_tipo"]').forEach(r => r.addEventListener('change', syncCategoria));
        syncCategoria();
    </script>

    @if (session()->has('success'))
        <script>
            Swal.fire({
                title: 'Feito!',
                html: `{!! session('success') !!}`,
                icon: 'success',
                confirmButtonColor: '#3c6cac',
                @if (session()->has('ficha_url'))
                    confirmButtonText: '<i class="fas fa-print"></i> Imprimir ficha',
                    showCancelButton: true,
                    cancelButtonText: 'Nova inscrição',
                @endif
            }).then(r => {
                @if (session()->has('ficha_url'))
                    if (r.isConfirmed) window.open(@json(session('ficha_url')), '_blank');
                @endif
            });
        </script>
    @endif
@endpush
