@extends('adminlte::page')

@section('title', 'Lotes - ')

@section('content_header')
    <div class="container-fluid">
        <h1 class="mb-0">Conferência dos lotes de {{ $ano }}</h1>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                        <h4 class="text-center">ESCOLAS PÚBLICAS</h4><br>

                        @if (!$publicaAmpla->isEmpty())
                        @foreach ($publicaAmpla as $idx => $lote)
                            @include('students.tbl_lote', 
                                ['alunos' => $lote, 'curr' => $idx, 'type' => 'Ampla Concorrência', 'orig' => 'Públicas'])
                        @endforeach                           
                        @endif

                        @if (!$publicaProximos->isEmpty())
                        @foreach ($publicaProximos as $idx => $lote)
                            @include('students.tbl_lote', 
                                ['alunos' => $lote, 'curr' => $idx, 'type' => 'Residentes Próximos', 'orig' => 'Públicas'])
                        @endforeach                           
                        @endif

                        @if (!$pcd->isEmpty())
                        @foreach ($pcd as $idx => $lote)
                            @include('students.tbl_lote', 
                                ['alunos' => $lote, 'curr' => $idx, 'type' => 'PCD', 'orig' => 'Públicas'])
                        @endforeach                           
                        @endif
                        
                    </div>
                    <div class="col-md-6" style="max-height: 400px; overflow-y: auto;">
                        <h4 class="text-center">ESCOLAS PARTICULARES</h4><br>
                        @if (!$privAmpla->isEmpty())
                        @foreach ($privAmpla as $idx => $lote)
                            @include('students.tbl_lote', 
                                ['alunos' => $lote, 'curr' => $idx, 'type' => 'Ampla Concorrência', 'orig' => 'Particulares'])
                        @endforeach                           
                        @endif  

                        @if (!$privProximos->isEmpty())
                        @foreach ($privProximos as $idx => $lote)
                            @include('students.tbl_lote', 
                                ['alunos' => $lote, 'curr' => $idx, 'type' => 'Residentes Próximos', 'orig' => 'Particulares'])
                        @endforeach                           
                        @endif 
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        $(document).ready(function() {
            $('.print').click(function() {
                let ref = $(this).attr('id');
                let res = document.getElementById(ref).innerHTML;
                let telinha = window.open();
                telinha.document.write(res);

                setTimeout(() => {
                    telinha.print();
                    telinha.close();
                }, 200);
            })
        });
    </script>
@endpush
