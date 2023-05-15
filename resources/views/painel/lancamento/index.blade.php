@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamentos do Cliente</h4>
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

                <h4 class="card-title">Listagem dos Lançamentos Mensais Registrados para o Cliente</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA efetivo - INI -->
                @if($user->cliente && $user->cliente->tipo != 'AG')
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#efetivo" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Efetivo Pecuário ( <code class="highlighter-rouge">{{$efetivos->count()}}</code> )
                                @can('create_efetivo')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('efetivo.create')}}'" style="color: goldenrod" title="Novo Lançamento - Efetivo Pecuário"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#fiscal" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Lançamento Fiscal ( <code class="highlighter-rouge">{{$movimentacaos->count()}}</code> )
                                @can('create_movimentacao')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('movimentacao.create')}}'" style="color: goldenrod" title="Novo Lançamento - Movimentação Fiscal"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                @endif
                <!-- Nav tabs - LISTA efetivo - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA efetivo - ATIVA - INI -->
                @if($user->cliente && $user->cliente->tipo != 'AG')
                <div class="tab-pane active" id="efetivo" role="tabpanel">
                    <table id="dt_efetivo" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th data-toggle="tooltip" title="Total de lançamentos referentes ao Efetivo Pecuário" style="text-align:center;">Lançamentos</th>
                            <th style="text-align:center;">Qtd. Compras</th>
                            <th style="text-align:center;">Qtd. Vendas</th>
                            <th style="text-align:center;">Qtd. Engordas</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($efetivos as $efetivo)
                        <tr>
                            <td>{{$efetivo->mes_ano}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$efetivo->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$efetivo->total}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$efetivo->compra}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$efetivo->venda}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$efetivo->engorda}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_efetivo')
                                <a href="{{route('efetivo.index', ['mes_referencia' => $efetivo->mes_referencia])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar o Efetivo Pecuário do mês"></i></a>
                            @endcan

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>
                @endif

                <div class="tab-pane" id="fiscal" role="tabpanel">
                    <table id="dt_fiscal" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th data-toggle="tooltip" title="Total de lançamentos referentes a Movimentação Fiscal" style="text-align:center;">Lançamentos</th>
                            <th style="text-align:center;">Qtd. Receitas</th>
                            <th style="text-align:center;">Qtd. Despesas</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacaos as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->total}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->receita}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$movimentacao->despesa}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_movimentacao')
                                <a href="{{route('movimentacao.index', ['mes_referencia' => $movimentacao->mes_referencia])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar a Movimentação Fiscal do mês"></i></a>
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

    @if($efetivos->count() > 0)
        <script>
            var table = $('#dt_efetivo').DataTable({
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

    @if($movimentacaos->count() > 0)
        <script>
            var table = $('#dt_fiscal').DataTable({
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
