@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Movimentação Fiscal do Cliente</h4>
        </div>
    </div>
</div>

@if(session()->has('message.level'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-{{ session('message.level') }}">
            {!! session('message.content') !!}
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Movimentação Mensal</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA efetivo - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#global" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Movimentação Global ( <code class="highlighter-rouge valor_mask" style="color:{{ ($saldo_global->saldo >= 0) ? 'blue' : 'redd' }}">{{($saldo_global->saldo ?? 'R$ 0,00')}}</code> )
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#efetiva" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Movimentação Efetiva ( <code class="highlighter-rouge valor_mask" style="color:blue">{{($saldo_efetivo->receita ?? 'R$ 0,00')}}</code> - <code class="highlighter-rouge valor_mask" style="color:red">{{($saldo_efetivo->despesa ?? 'R$ 0,00')}}</code> )
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#futura" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Movimentação Futura ( <code class="highlighter-rouge valor_mask" style="color:blue">{{($saldo_futuro->receita ?? 'R$ 0,00')}}</code> - <code class="highlighter-rouge valor_mask" style="color:red">{{($saldo_futuro->despesa ?? 'R$ 0,00')}}</code> )
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA efetivo - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA efetivo - ATIVA - INI -->
                <div class="tab-pane active" id="global" role="tabpanel">
                    <table id="dt_global" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th style="text-align:center;">Receitas</th>
                            <th style="text-align:center;">Despesas</th>
                            <th data-toggle="tooltip" title="Saldo remanescente referente a Movimentação Fiscal do mês" style="text-align:center;">Saldo</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacao_global as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->receita}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->despesa}}</td>
                            <td style="text-align:center;vertical-align: middle;color: {{ ($movimentacao->receita >= $movimentacao->despesa) ? 'blue' : 'red' }}" class="valor_mask">{{$movimentacao->saldo}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_financeiro')
                            <a href="{{route('financeiro.list', ['mes_referencia' => $movimentacao->mes_referencia, 'status_movimentacao' => 'GB'])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar a Movimentação Fiscal do mês"></i></a>
                            @endcan

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>

                <div class="tab-pane" id="efetiva" role="tabpanel">
                    <table id="dt_efetiva" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th style="text-align:center;">Receitas</th>
                            <th style="text-align:center;">Despesas</th>
                            <th data-toggle="tooltip" title="Saldo remanescente referente a Movimentação Fiscal do mês" style="text-align:center;">Saldo</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacao_efetiva as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->receita}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->despesa}}</td>
                            <td style="text-align:center;vertical-align: middle;color: {{ ($movimentacao->receita >= $movimentacao->despesa) ? 'blue' : 'red' }}" class="valor_mask">{{$movimentacao->saldo}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_financeiro')
                                <a href="{{route('financeiro.list', ['mes_referencia' => $movimentacao->mes_referencia, 'status_movimentacao' => 'PG'])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar a Movimentação Fiscal do mês"></i></a>
                            @endcan

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>

                <div class="tab-pane" id="futura" role="tabpanel">
                    <table id="dt_futura" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th style="text-align:center;">Receitas</th>
                            <th style="text-align:center;">Despesas</th>
                            <th data-toggle="tooltip" title="Saldo remanescente referente a Movimentação Fiscal do mês" style="text-align:center;">Saldo</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacao_futura as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->receita}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$movimentacao->despesa}}</td>
                            <td style="text-align:center;vertical-align: middle;color: {{ ($movimentacao->receita >= $movimentacao->despesa) ? 'blue' : 'red' }}" class="valor_mask">{{$movimentacao->saldo}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_financeiro')
                            <a href="{{route('financeiro.list', ['mes_referencia' => $movimentacao->mes_referencia, 'status_movimentacao' => 'PD'])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar a Movimentação Fiscal do mês"></i></a>
                            @endcan

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>
            </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script-js')
    <!-- Required datatable js -->
    <script src="{{asset('nazox/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('nazox/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('nazox/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
   <!-- Datatable init js -->
    <script src="{{asset('nazox/assets/js/pages/datatables.init.js')}}"></script>

    <!-- form mask -->
    <script src="{{asset('nazox/assets/libs/inputmask/jquery.inputmask.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $(".valor_mask").inputmask("R$ (.999){+|1},99",{numericInput:true, placeholder:"0"});
        });
    </script>

    @if($movimentacao_global->count() > 0)
        <script>
            var table_global = $('#dt_global').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 0, "desc" ]],
                columnDefs: [
                    {
                        targets: [ 0 ],
                        visible: false,
                    },
                ],
            });
        </script>
    @endif

    @if($movimentacao_efetiva->count() > 0)
        <script>
            var table_efetiva = $('#dt_efetiva').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 0, "desc" ]],
                columnDefs: [
                    {
                        targets: [ 0 ],
                        visible: false,
                    },
                ],
            });
        </script>
    @endif

    @if($movimentacao_futura->count() > 0)
    <script>
        var table_futura = $('#dt_futura').DataTable({
            language: {
                url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
            },
            "order": [[ 0, "desc" ]],
            columnDefs: [
                {
                    targets: [ 0 ],
                    visible: false,
                },
            ],
        });
    </script>
    @endif

@endsection
