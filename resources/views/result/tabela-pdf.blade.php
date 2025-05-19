{{-- Tabela modelo do resultado GERAL (PDF) --}}
<div style="page-break-inside: avoid; margin-bottom: 2rem;">
    <table border="1" width="100%">
        <thead>
            <tr>
                <td colspan="{{ $flag ? '5' : '6' }}" align="center">
                    <p style="text-transform: uppercase; margin: 0;">
                        <strong><u style="font-size: 20px;">RESULTADO {{ $res }}</u></strong><br>
                        <img width="80" src="{{ public_path('img/eeepjas-icon.png') }}" alt="Brasão da Escola"><br>
                        EEEP DR. JOSÉ ALVES DA SILVEIRA<br>
                        EDITAL {{ $edital }} - SELEÇÃO DE NOVOS ALUNOS {{ $ano }} - 
                        <strong>Escola {{ $type }}</strong><br>
                        <b>{{ $flag ? 'CLASSIFICADOS' : 'CLASSIFICÁVEIS' }}</b> DO <strong>CURSO TÉCNICO EM {{ strtoupper($cursoNome) }}</strong><br>
                        @if($flag)
                            @switch($origin)
                                @case('PCD')
                                    <b><u>PESSOAS COM DEFICIÊNCIA</u></b>
                                    @break
                                @case('PUBLICA-AMPLA')
                                @case('PRIVATE-AMPLA')
                                    <b><u>AMPLA CONCORRÊNCIA</u></b>
                                    @break
                                @case('PUBLICA-PROX-EEEP')
                                @case('PRIVATE-PROX-EEEP')
                                    <b><u>RESIDENTES PRÓXIMOS</u></b>
                                    @break
                            @endswitch
                        @endif
                    </p>
                </td>
            </tr>
            <tr style="margin-top: 5px;">
                <th>Class.</th>
                <th>Nome</th>
                @if (!$flag) <th>Categoria</th> @endif
                <th>{{ $flag ? 'Média' : '' }} LP</th>
                <th>{{ $flag ? 'Média' : '' }} MT</th>
                <th style="white-space: nowrap;">{{ $flag ? 'Média' : '' }} FINAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alunos as $aluno)
                <tr>
                    <td align="center">{{ $loop->iteration }}º</td>
                    <td style="white-space: nowrap;">{{ $aluno->student->nome }}</td>
                    @if (!$flag)
                        <td>
                            @switch($aluno->student->origem)
                                @case('PUBLICA-AMPLA')
                                @case('PRIVATE-AMPLA')
                                    AMPLA CONCORRÊNCIA
                                    @break
                                @case('PUBLICA-PROX-EEEP')
                                @case('PRIVATE-PROX-EEEP')
                                    RESIDENTES PRÓXIMOS
                                    @break
                                @case('PCD')
                                    PESSOAS COM DEFICIÊNCIA
                                    @break
                            @endswitch
                        </td>
                    @endif
                    <td align="center">{{ number_format($aluno->student->media_pt, 2, ',', '') }}</td>
                    <td align="center">{{ number_format($aluno->student->media_mt, 2, ',', '') }}</td>
                    <td align="center">{{ number_format($aluno->student->media_final, 2, ',', '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 0.15rem;">
        <p style="text-align: right; margin: 0;">
            Quixeramobim/CE, {{ $day }} de {{ ucfirst($month) }} de {{ $year }}
        </p>

        <div style="text-align: center; margin-top: 0.25rem;">
            <img style="max-width: 400px;" src="{{ public_path('img/assinatura.png') }}" alt="Assinatura da diretora escolar Irecê Fernandes">
        </div>
    </div>
</div>