@php
    $box = fn (bool $on) => $on ? 'X' : '&nbsp;';
    $nota = fn ($v) => $v !== null ? number_format((float) $v, 2, ',', '') : '';
    $opcao = $courses->search(fn ($c) => $c->id === $student->curso_id);
    $origem = (string) $student->origem;
    $publica = $student->isEscolaPublica();
    $dataInscricao = optional($student->created_at)->format('d/m/Y') ?? now()->format('d/m/Y');
    $vias = ['1ª VIA — ESCOLA', '2ª VIA — RESPONSÁVEL'];
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha de inscrição nº {{ $student->numero_inscricao }} - {{ $student->nome }}</title>
    <style>
        @page { size: A4; margin: 12mm; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #e9ecef; font-family: Arial, Helvetica, sans-serif; font-size: 12.5px; color: #000; }

        .toolbar { position: sticky; top: 0; z-index: 10; display: flex; gap: 12px; align-items: center;
                   justify-content: center; padding: 10px; background: #343a40; color: #fff; }
        .toolbar button { background: #3c6cac; color: #fff; border: 0; border-radius: 4px; padding: 8px 18px;
                          font-size: 14px; cursor: pointer; }
        .toolbar label { font-size: 14px; cursor: pointer; }

        .page { width: 210mm; min-height: 297mm; margin: 16px auto; padding: 12mm; background: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,.2); position: relative; }
        .via { position: absolute; top: 4mm; right: 12mm; font-size: 10px; font-weight: bold; letter-spacing: .5px; }

        /* table-layout: fixed impede que textos longos alarguem a tabela além da folha */
        table.ficha { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.ficha td { border: 1px solid #000; padding: 4px 6px; vertical-align: middle; overflow-wrap: anywhere; }
        td.label { width: 23%; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 11.5px; }
        .header { text-align: center; font-size: 11.5px; line-height: 1.35; }
        .header b { font-size: 12.5px; }
        .logo { width: 23%; text-align: center; }
        .logo img { width: 100px; height: 100px; max-width: 100%; object-fit: contain; }

        .line { display: flex; flex-wrap: wrap; align-items: baseline; gap: 2px 6px; margin: 3px 0; }
        .fill { flex: 1 1 0; min-width: 0; border-bottom: 1px solid #000; min-height: 16px; padding: 0 4px;
                font-weight: bold; overflow-wrap: anywhere; }
        .fill.sm { flex: 0 1 auto; min-width: 70px; }
        .chk { display: inline-block; min-width: 28px; text-align: center; font-weight: bold; }

        h3 { text-align: center; font-size: 13px; margin: 14px 0 6px; text-decoration: underline; }
        .termo { text-align: justify; line-height: 1.7; }
        .termo .fill { display: inline-block; min-width: 260px; max-width: 100%; }
        .sign { margin: 34px auto 0; width: 70%; border-top: 1px solid #000; text-align: center; padding-top: 3px; font-size: 11.5px; }

        table.docs { width: 100%; border-collapse: collapse; }
        table.docs td { padding: 3px 0; }
        table.docs td.opt { width: 70px; white-space: nowrap; }

        .obs .line { margin: 5px 0; }
        .obs .k { font-weight: bold; }

        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            html, body { width: 100%; }
            .page { margin: 0; padding: 0; width: 100%; max-width: 186mm; min-height: auto; box-shadow: none;
                    page-break-after: always; }
            .page:last-of-type { page-break-after: auto; }
            .via { top: -6mm; right: 0; }
            body:not(.duas-vias) .page.via-2 { display: none; }
        }
        body:not(.duas-vias) .page.via-2 { display: none; }
    </style>
</head>
<body class="duas-vias">
    <div class="toolbar">
        <button type="button" onclick="window.print()">🖨 Imprimir ficha</button>
        <label><input type="checkbox" id="duas-vias" checked> Imprimir 2ª via para o responsável</label>
    </div>

    @foreach ($vias as $i => $via)
    <div class="page via-{{ $i+1 }}">
        <div class="via">{{ $via }}</div>

        <table class="ficha">
            <tr>
                <td class="logo"><img width="70" height="70" src="{{ asset('img/eeepjas-icon.png') }}" alt="Brasão da escola"></td>
                <td class="header">
                    <b>GOVERNO DO ESTADO DO CEARÁ</b><br>
                    SECRETARIA DA EDUCAÇÃO BÁSICA<br>
                    12ª COORDENADORIA REGIONAL DE DESENVOLVIMENTO DA EDUCAÇÃO<br>
                    <u>EEEP DR. JOSÉ ALVES DA SILVEIRA</u><br>
                    Av: Humberto Sena – 250 &nbsp; Contato: (88) 3441-3170<br>
                    <b>FICHA DE INSCRIÇÃO — SELEÇÃO {{ $student->process->ano }}</b>
                </td>
            </tr>
            <tr>
                <td class="label">
                    Identificação do aluno<br><br>
                    Nº de inscrição:<br>
                    <span style="font-size: 18px;">{{ $student->numero_inscricao }}</span>
                </td>
                <td>
                    <div class="line">Nome do aluno: <span class="fill">{{ $student->nome }}</span></div>
                    <div class="line">Data de nascimento:
                        <span class="fill">{{ \Carbon\Carbon::parse($student->data_nascimento)->format('d/m/Y') }}</span></div>
                    <div class="line">Endereço – Rua/Av.: <span class="fill">{{ $student->endereco }}</span></div>
                    <div class="line">Nº <span class="fill sm">{{ $student->numero }}</span>
                        CEP <span class="fill sm">{{ $student->cep }}</span>
                        Bairro <span class="fill">{{ $student->bairro }}</span></div>
                    <div class="line">Fones: <span class="fill">{{ $student->telefone_1 }}</span>
                        <span class="fill">{{ $student->telefone_2 }}</span></div>
                </td>
            </tr>
            <tr>
                <td class="label">Informações sobre a escola de origem</td>
                <td>
                    <div class="line">Escola em que cursou o 9º ano:
                        (<span class="chk">{!! $box($student->escola_origem_tipo === 'ESTADUAL') !!}</span>) Estadual
                        (<span class="chk">{!! $box($student->escola_origem_tipo === 'MUNICIPAL') !!}</span>) Municipal
                        (<span class="chk">{!! $box($student->escola_origem_tipo === 'PARTICULAR') !!}</span>) Particular
                    </div>
                    <div class="line">Escola: <span class="fill">{{ $student->escola_origem_nome }}</span></div>
                </td>
            </tr>
            <tr>
                <td class="label">Opção de curso</td>
                <td>
                    <div>CURSOS:
                        @foreach ($courses as $n => $course)
                            <b>{{ $n + 1 }}.</b> {{ mb_strtoupper($course->nome) }}@if (!$loop->last) &nbsp; @endif
                        @endforeach
                    </div>
                    <div class="line" style="justify-content: center; margin-top: 6px;">
                        (<span class="chk">{{ $opcao !== false ? $opcao + 1 : '' }}</span>) OPÇÃO DE CURSO
                        — <b>{{ mb_strtoupper(optional($student->course)->nome) }}</b>
                    </div>
                </td>
            </tr>
        </table>

        <h3>TERMO DE CIÊNCIA</h3>
        <div class="termo">
            Eu, <span class="fill">{{ $student->responsavel_nome }}</span>
            (<b>{{ $student->responsavel_parentesco }}</b>), responsável pelo(a) aluno(a) acima citado, estou ciente das
            normas do edital de adesão da EEEP DR. JOSÉ ALVES DA SILVEIRA e declaro que fiz a escolha do Curso Técnico de
            Nível Médio de acordo com a opção do(a) aluno(a).
        </div>
        <div style="text-align: right; margin-top: 6px;">Quixeramobim, {{ $dataInscricao }}.</div>
        <div class="sign">Assinatura do(a) responsável pelo aluno</div>

        <h3>ENTREGA DE DOCUMENTOS E OBSERVAÇÕES</h3>
        <table class="docs">
            @foreach ([
                'doc_comprovante_idade' => 'Entregou a cópia do comprovante de idade (Registro de Nascimento; RG)',
                'doc_comprovante_residencia' => 'Entregou a cópia do comprovante de residência',
                'doc_historico' => 'Entregou a cópia do histórico ou declaração com notas do 6º ao 9º ano',
            ] as $field => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="opt">(<span class="chk">{!! $box($student->$field) !!}</span>) SIM</td>
                    <td class="opt">(<span class="chk">{!! $box(!$student->$field) !!}</span>) Não</td>
                </tr>
            @endforeach
        </table>

        <h3>OBSERVAÇÕES</h3>
        <div class="obs">
            <div class="line"><span class="k">Média aritmética das notas do 6º ao 9º ano:</span>
                <span class="fill sm">{{ $nota($student->media_final) }}</span></div>
            <div class="line"><span class="k">Média aritmética da disciplina de Língua Portuguesa:</span>
                <span class="fill sm">{{ $nota($student->media_pt) }}</span></div>
            <div class="line"><span class="k">Média aritmética da disciplina de Matemática:</span>
                <span class="fill sm">{{ $nota($student->media_mt) }}</span></div>
            <div class="line"><span class="k">Ampla Concorrência:</span>
                (<span class="chk">{!! $box(str_ends_with($origem, 'AMPLA')) !!}</span>)</div>
            <div class="line"><span class="k">Bairro de Território da Escola:</span>
                (<span class="chk">{!! $box(str_ends_with($origem, 'PROX-EEEP')) !!}</span>) Qual:
                <span class="fill">{{ str_ends_with($origem, 'PROX-EEEP') ? $student->bairro : '' }}</span></div>
            <div class="line"><span class="k">Necessidades Especiais:</span>
                (<span class="chk">{!! $box($origem === 'PCD') !!}</span>)</div>
            <div class="line"><span class="k">Escola Pública:</span>
                (<span class="chk">{!! $box($publica) !!}</span>)
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="k">Escola Particular:</span>
                (<span class="chk">{!! $box(!$publica) !!}</span>)</div>
        </div>

        <div class="sign">
            <b>({{ optional($student->inscritoPor)->name }})</b><br>
            Ass. do funcionário responsável pela inscrição
        </div>
    </div>
    @endforeach

    <script>
        const toggle = document.getElementById('duas-vias');
        toggle.addEventListener('change', () => document.body.classList.toggle('duas-vias', toggle.checked));
    </script>
</body>
</html>
