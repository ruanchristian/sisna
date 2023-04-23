@extends('adminlte::master')

@if ($students->isNotEmpty())
    <table class="resultado">
        <thead>
            <tr>
                <td colspan='5'>
                    <p class="text-center">
                        <img width="80rem" class="img-fluid" src="{{ asset('img/eeepjas-icon.png') }}" alt="Logo"><br>
                        <b>EEEP Dr. José Alves da Silveira</b><br>
                        <b>Resultado: {{ $origem }}</b>
                    </p>
                </td>
            </tr>
            <tr>
                <th>Class</th>
                <th>Nome do estudante</th>
                <th>Média L.P</th>
                <th>Média M.T</th>
                <th>Média Final</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $student->nome }}</td>
                    <td>{{ number_format($student->media_pt, 2) }}</td>
                    <td>{{ number_format($student->media_mt, 2) }}</td>
                    <td>{{ number_format($student->media_final, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Não há alunos classificados na categoria {{ $origem }}.</p>
@endif
