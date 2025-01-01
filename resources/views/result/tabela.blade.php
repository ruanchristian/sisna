@php
    setlocale(LC_TIME, 'pt_BR.utf-8');
    $dia = date('d');
    $mes = strftime('%B', mktime(0, 0, 0, date('m')));
@endphp

<div id="res_#{{ $cursoNome. $origin. $type. $flag }}" style="text-align: center;">
    <table border="1" width="100%">
        <thead>
            <tr>
                <td colspan="6">
                    <p style="text-transform: uppercase;" align="center" class="text-center">
                        <u><b>RESULTADO PRELIMINAR</b></u><br>
                        <img  width="75rem;" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                        EEEP DR. JOSÉ ALVES DA SILVEIRA<br>
                        EDITAL 01/2023 - SELEÇÃO DE NOVOS ALUNOS {{ $ano }} - <b>Escola {{ $type }}</b><br>
                        {{ $flag ? 'CLASSIFICADOS' : 'CLASSIFICÁVEIS' }} DO <b>CURSO TÉCNICO EM {{ $cursoNome }}<b><br>
                        @if($flag)     
                         @switch($origin)
                            @case('PCD')
                                <u>PESSOAS COM DEFICIÊNCIA</u>
                            @break

                            @case('PUBLICA-AMPLA')
                            @case('PRIVATE-AMPLA')
                                <u>AMPLA CONCORRÊNCIA</u>
                            @break

                            @case('PUBLICA-PROX-EEEP')
                            @case('PRIVATE-PROX-EEEP')
                                <u>RESIDENTES PRÓXIMOS</u>
                            @break
                         @endswitch
                        @endif
                    </p>
                </td>
            </tr>
            <th>Class</th>
            <th>Nome</th>
            @if (!$flag) <th>Categoria</th> @endif
            <th>{{ $flag ? 'Média' : '' }} LP</th>
            <th>{{ $flag ? 'Média' : '' }} MT</th>
            <th>{{ $flag ? 'Média' : '' }} FINAL</th>
        </thead>

        <tbody>
            @foreach ($alunos as $aluno)
                <tr>
                    <td align="center">{{ $loop->iteration }}º</td>
                    <td>{{ $aluno->student->nome }}</td>
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
                    <td align="center">{{ number_format($aluno->student->media_pt, 2, '.', '') }}</td>
                    <td align="center">{{ number_format($aluno->student->media_mt, 2, '.', '') }}</td>
                    <td align="center">{{ number_format($aluno->student->media_final, 2, '.', '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table><br>
    <label style="float: right;">Quixeramobim/CE, {{ $dia.' de '.$mes.' de '.date('Y') }}</label>
    <img style="margin-left: 11rem;" width="370rem" src="{{ asset('img/assinatura.png') }}" alt="Assinatura da diretora escolar Irecê Fernandes">
</div><br>
<x-adminlte-button 
class="print" id="res_#{{ $cursoNome.$origin.$type.$flag }}" 
icon="fas fa-print" style="float: right;" label="Imprimir" theme="primary" />
<br><br><br>