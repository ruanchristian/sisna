@extends('adminlte::page')

@section('title', 'Painel - ')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Painel</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <span class="breadcrumb-item">Bem vindo(a): <b>{{ Auth::user()->name }}</b></span>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $processesCount }}</h3>
                        <p>Processos seletivos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-arrows-down-to-people"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('process.index') }}" class="small-box-footer"> Ver mais
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $coursesCount }}</h3>
                        <p>Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('course.index') }}" class="small-box-footer"> Ver mais
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $studentsCount > 99999 ? '99999+' : $studentsCount }}</h3>
                        <p>Alunos cadastrados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="javascript:void(0);" class="small-box-footer"> Ver mais
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $adminsCount }}</h3>
                        <p>{{ $adminsCount == 1 ? 'Administrador' : 'Administradores' }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-code"></i>
                    </div>
                    @can('isAdmin', Auth::user())
                        <a href="{{ route('user.index') }}" class="small-box-footer"> Ver mais
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        @cannot('isAdmin', Auth::user())
            <x-adminlte-card title="Processos seletivos em andamento" theme="primary" icon="fas fa-arrows-down-to-people">
                @if ($ativos->isEmpty())
                    <b class="text-danger">Não há processos em andamento.</b>
                @else
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ano</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ativos as $p)
                                <tr>
                                    <td>{{ $p->ano }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-success dropdown-toggle drop-d" type="button"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-user-graduate"></i>
                                                Participantes
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('student.index', $p->id) }}">Cadastrar
                                                        participantes</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('student.visualization', $p->id) }}">Ver
                                                        participantes</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </x-adminlte-card>
        @endcannot

        {{-- ========== INDICADORES ========== --}}
        <div class="row">
            <div class="col-lg col-md-4 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-arrow-trend-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Média inscritos por processo</span>
                        <span class="info-box-number">{{ number_format($mediaPorProcesso, 1, ',', '.') }}
                            <small>inscritos</small></span>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-4 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-orange"><i class="fas fa-fire"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ano mais competitivo</span>
                        <span class="info-box-number">
                            {{ $anoMaisCompetitivo->ano ?? '—' }}
                            <small>({{ number_format($anoMaisCompetitivo->students_count ?? 0, 0, ',', '.') }})</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Curso com mais inscritos (Geral)</span>
                        <span class="info-box-number">
                            {{ $cursoMaisInscritos->nome ?? '—' }}
                            <small>({{ number_format($cursoMaisInscritos->students_count ?? 0, 0, ',', '.') }})</small>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg col-md-6 col-sm-6">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-star"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Média final geral</span>
                        <span class="info-box-number">{{ number_format($mediaFinalGeral, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="secao-filtravel">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <span class="secao-titulo">
                    <i class="fas fa-filter"></i> Gráficos filtráveis por processo seletivo
                </span>
                <div class="d-flex align-items-center">
                    <label for="filtroProcesso" class="mb-0 mr-2">Processo:</label>
                    <select id="filtroProcesso" class="form-control form-control-sm" style="width:auto">
                        <option value="all">Todos os processos</option>
                        @foreach ($processos->sortByDesc('ano') as $p)
                            <option value="{{ $p->id }}">Processo {{ $p->ano }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <x-adminlte-card title="Inscrições por curso" theme="primary" icon="fas fa-chart-pie">
                        <div style="height:320px"><canvas id="graficoCursos"></canvas></div>
                    </x-adminlte-card>
                </div>
                <div class="col-lg-4">
                    <x-adminlte-card title="Inscrições por categoria" theme="primary" icon="fas fa-users-viewfinder">
                        <div style="height:320px"><canvas id="graficoOrigem"></canvas></div>
                    </x-adminlte-card>
                </div>
                <div class="col-lg-4">
                    <x-adminlte-card title="Média final por curso" theme="primary" icon="fas fa-chart-simple">
                        <div style="height:320px"><canvas id="graficoMedia"></canvas></div>
                    </x-adminlte-card>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5">
                    <x-adminlte-card title="Média final por categoria" theme="primary" icon="fas fa-star-half-stroke">
                        <div style="height:340px"><canvas id="graficoMediaOrigem"></canvas></div>
                    </x-adminlte-card>
                </div>
                <div class="col-lg-7">
                    <x-adminlte-card title="Categorias por curso" theme="primary" icon="fas fa-table-cells-large">
                        <div style="height:340px"><canvas id="graficoCursoOrigem"></canvas></div>
                    </x-adminlte-card>
                </div>
            </div>
        </div>

        <div class="secao-geral">
            <div class="mb-3">
                <span class="secao-titulo">
                    <i class="fas fa-calendar-alt"></i> Visão GERAL
                </span>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <x-adminlte-card title="Crescimento das inscrições" theme="secondary"
                        icon="fas fa-chart-line">
                        <div style="height:320px"><canvas id="graficoLinha"></canvas></div>
                    </x-adminlte-card>
                </div>
                <div class="col-lg-6">
                    <x-adminlte-card title="Categorias por processo" theme="secondary" icon="fas fa-layer-group">
                        <div style="height:320px"><canvas id="graficoOrigemAno"></canvas></div>
                    </x-adminlte-card>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <x-adminlte-card title="Evolução por ano" theme="secondary" icon="fas fa-table">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Ano</th>
                                    <th class="text-right">Inscrições</th>
                                    <th class="text-right">Variação x Ano anterior</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($evolucao->reverse() as $e)
                                    <tr>
                                        <td>{{ $e['ano'] }}</td>
                                        <td class="text-right">{{ number_format($e['total'], 0, ',', '.') }}</td>
                                        <td class="text-right">
                                            @if (is_null($e['variacao']))
                                                <span class="text-muted">—</span>
                                            @elseif ($e['variacao'] >= 0)
                                                <span class="text-success"><i class="fas fa-arrow-up"></i>
                                                    {{ number_format($e['variacao'], 1, ',', '.') }}%</span>
                                            @else
                                                <span class="text-danger"><i class="fas fa-arrow-down"></i>
                                                    {{ number_format(abs($e['variacao']), 1, ',', '.') }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Sem dados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </x-adminlte-card>
                </div>
                <div class="col-lg-6">
                    <x-adminlte-card title="Top 5 cursos com mais inscritos" theme="secondary"
                        icon="fas fa-ranking-star">
                        @php $maxCurso = max($topCursos->max('students_count'), 1); @endphp
                        @forelse ($topCursos as $i => $c)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span><b>{{ $i + 1 }}º</b> {{ $c->nome }}</span>
                                    <span>{{ number_format($c->students_count, 0, ',', '.') }}</span>
                                </div>
                                <div class="progress" style="height:10px">
                                    <div class="progress-bar"
                                        style="width: {{ ($c->students_count / $maxCurso) * 100 }}%; background-color: {{ $c->cor_curso }}">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <span class="text-muted">Sem dados.</span>
                        @endforelse
                    </x-adminlte-card>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        $(function() {
            const dados = @json($dados);
            const origensMeta = @json(array_values($origens));
            const origemPorProcesso = @json($origemPorProcesso);
            const evolucao = @json($evolucao);

            const vazio = {
                cursos: [],
                origens: origensMeta.map(() => 0),
                medias: origensMeta.map(() => 0)
            };
            const fmt = n => Number(n).toLocaleString('pt-BR');
            const fmtNota = n => Number(n).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;
            const tooltipPct = {
                callbacks: {
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = total ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                        return `${ctx.label}: ${fmt(ctx.parsed)} (${pct}%)`;
                    }
                }
            };

            const graficoCursos = new Chart(document.getElementById('graficoCursos'), {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: []
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: tooltipPct
                    }
                }
            });

            const graficoOrigem = new Chart(document.getElementById('graficoOrigem'), {
                type: 'doughnut',
                data: {
                    labels: origensMeta.map(o => o.label),
                    datasets: [{
                        data: [],
                        backgroundColor: origensMeta.map(o => o.color)
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: tooltipPct
                    }
                }
            });

            // Média final por curso
            const graficoMedia = new Chart(document.getElementById('graficoMedia'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Média final',
                        data: [],
                        backgroundColor: [],
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `Média final: ${fmtNota(ctx.parsed.x)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const graficoMediaOrigem = new Chart(document.getElementById('graficoMediaOrigem'), {
                type: 'bar',
                data: {
                    labels: origensMeta.map(o => o.label),
                    datasets: [{
                        label: 'Média final',
                        data: [],
                        backgroundColor: origensMeta.map(o => o.color),
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `Média final: ${fmtNota(ctx.parsed.x)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const graficoCursoOrigem = new Chart(document.getElementById('graficoCursoOrigem'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: origensMeta.map(o => ({
                        label: o.label,
                        data: [],
                        backgroundColor: o.color
                    }))
                },
                options: {
                    indexAxis: 'y',
                    interaction: {
                        mode: 'index',
                        axis: 'y',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            // oculta categorias com 0 inscritos
                            filter: item => item.parsed.x > 0,
                            callbacks: {
                                label: ctx => `${ctx.dataset.label}: ${fmt(ctx.parsed.x)}`,
                                footer: items => {
                                    const total = items.reduce((s, i) => s + i.parsed.x, 0);
                                    return `Total: ${fmt(total)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });

            function atualizar(id) {
                const d = dados[id] || vazio;

                // Inscrições por curso
                graficoCursos.data.labels = d.cursos.map(c => c.name);
                graficoCursos.data.datasets[0].data = d.cursos.map(c => c.total);
                graficoCursos.data.datasets[0].backgroundColor = d.cursos.map(c => c.color);
                graficoCursos.update();

                // Inscrições por categoria
                graficoOrigem.data.datasets[0].data = d.origens;
                graficoOrigem.update();

                // Média final por curso (ordenada da maior para a menor)
                const porMedia = [...d.cursos].sort((a, b) => b.media - a.media);
                graficoMedia.data.labels = porMedia.map(c => c.name);
                graficoMedia.data.datasets[0].data = porMedia.map(c => c.media);
                graficoMedia.data.datasets[0].backgroundColor = porMedia.map(c => c.color);
                graficoMedia.update();

                // Média final por categoria
                graficoMediaOrigem.data.datasets[0].data = d.medias;
                graficoMediaOrigem.update();

                // Categorias por curso
                graficoCursoOrigem.data.labels = d.cursos.map(c => c.name);
                graficoCursoOrigem.data.datasets.forEach((ds, i) => {
                    ds.data = d.cursos.map(c => c.origens[i]);
                });
                graficoCursoOrigem.update();
            }

            $('#filtroProcesso').on('change', function() {
                atualizar(this.value);
            });

            atualizar('all');

            new Chart(document.getElementById('graficoLinha'), {
                type: 'line',
                data: {
                    labels: evolucao.map(p => p.ano),
                    datasets: [{
                        label: 'Inscrições',
                        data: evolucao.map(p => p.total),
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, .15)',
                        fill: true,
                        tension: .3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: evolucao.map(p => p.estado ? '#28a745' : '#17a2b8'),
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('graficoOrigemAno'), {
                type: 'bar',
                data: {
                    labels: origemPorProcesso.map(p => p.ano),
                    datasets: origensMeta.map((o, i) => ({
                        label: o.label,
                        data: origemPorProcesso.map(p => p.origens[i]),
                        backgroundColor: o.color
                    }))
                },
                options: {
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        .secao-filtravel {
            background: rgba(0, 123, 255, .08);
            border: 1px solid rgba(0, 123, 255, .35);
            border-left: 5px solid #007bff;
            border-radius: .5rem;
            padding: 1rem 1rem 0;
            margin-bottom: 1.5rem;
        }

        .secao-filtravel .secao-titulo {
            color: #007bff;
            font-weight: 600;
        }

        .secao-geral {
            background: rgba(108, 117, 125, .07);
            border: 1px solid rgba(108, 117, 125, .3);
            border-left: 5px solid #6c757d;
            border-radius: .5rem;
            padding: 1rem 1rem 0;
            margin-bottom: 1.5rem;
        }

        .secao-geral .secao-titulo {
            color: #6c757d;
            font-weight: 600;
        }
    </style>
@endpush
