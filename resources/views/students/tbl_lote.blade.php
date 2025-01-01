<div id="lote_#{{ $type.$curr.$orig }}" style="text-align: center;">
    <table border="1" width="100%">
        <thead>
            <tr>
                <td colspan="7">
                    <p style="text-transform: uppercase;" align="center" class="text-center">
                        @if ($type === "PCD")
                            ALUNOS - <u>PCD</u>
                        @else
                            ALUNOS DE ESCOLAS {{ $orig }} - <u>{{ $type }}</u>
                        @endif
                        <br>
                        <b>{{ $curr+1 }}º Lote</b>
                    </p>
                </td>
            </tr>
            <th>Idx</th>
            <th>Nome</th>
            <th>Data de nasc.</th>
            <th>Curso</th>
            <th>LP</th>
            <th>MT</th>
            <th>FINAL</th>
        </thead>

        <tbody>
            @foreach ($alunos as $idx => $aluno)
                <tr>
                    <td align="center">{{ $idx+1 }}</td>
                    <td>{{ $aluno->nome }}</td>
                    <td align="center">{{ date('d/m/Y', strtotime($aluno->data_nascimento)) }}</td>
                    <td align="center">{{ $cursos->find($aluno->curso_id)->nome; }}</td>
                    <td align="center">{{ number_format($aluno->media_pt, 2, '.', '') }}</td>
                    <td align="center">{{ number_format($aluno->media_mt, 2, '.', '') }}</td>
                    <td align="center">{{ number_format($aluno->media_final, 2, '.', '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table><br>
</div>
<x-adminlte-button 
class="print" id="lote_#{{ $type.$curr.$orig }}" 
icon="fas fa-print" style="float: right;" label="Imprimir" theme="primary" />
<br><br><br>