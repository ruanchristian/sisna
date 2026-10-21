@extends('adminlte::page')

@section('title', 'Auditoria - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Auditoria de Segurança</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Evento</th>
                            <th>Modelo</th>
                            <th>Usuário</th>
                            <th>Data</th>
                            <th>IP</th>
                            <th class="text-center">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($audits as $audit)
                            @php
                                $eventBadgeClass = match ($audit->event) {
                                    'created' => 'badge-success',
                                    'updated' => 'badge-primary',
                                    'deleted' => 'badge-danger',
                                    'restored' => 'badge-warning',
                                    'retrieved' => 'badge-secondary',
                                    default => 'badge-dark',
                                };
                            @endphp

                            <tr>
                                <td>{{ $audit->id }}</td>
                                <td>
                                    <span class="badge {{ $eventBadgeClass }} text-uppercase">{{ $audit->event }}</span>
                                </td>
                                <td>
                                    {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}
                                </td>
                                <td>
                                    {{ $audit->user->name ?? 'Sistema' }}
                                </td>
                                <td>{{ optional($audit->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $audit->ip_address ?? '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#auditModal{{ $audit->id }}">
                                        Ver
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="auditModal{{ $audit->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalhes da auditoria #{{ $audit->id }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <dl class="row mb-0">
                                                <dt class="col-sm-3">Evento</dt>
                                                <dd class="col-sm-9 text-uppercase">{{ $audit->event }}</dd>

                                                <dt class="col-sm-3">Modelo</dt>
                                                <dd class="col-sm-9">{{ $audit->auditable_type }} (ID {{ $audit->auditable_id }})</dd>

                                                <dt class="col-sm-3">Usuário</dt>
                                                <dd class="col-sm-9">{{ $audit->user->name ?? 'Sistema' }}</dd>

                                                <dt class="col-sm-3">URL</dt>
                                                <dd class="col-sm-9 text-break">{{ $audit->url ?? '-' }}</dd>

                                                <dt class="col-sm-3">IP</dt>
                                                <dd class="col-sm-9">{{ $audit->ip_address ?? '-' }}</dd>

                                                <dt class="col-sm-3">User Agent (navegador)</dt>
                                                <dd class="col-sm-9 text-break">{{ $audit->user_agent ?? '-' }}</dd>
                                            </dl>

                                            <hr>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="font-weight-bold">Valores antigos</h6>
                                                    <pre class="bg-light p-3 rounded mb-0 text-wrap">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="font-weight-bold">Valores novos</h6>
                                                    <pre class="bg-light p-3 rounded mb-0 text-wrap">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Nenhum registro de auditoria encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $audits->links('pagination::bootstrap-4') }}
        </div>
    </div>
@stop