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

                <!-- Nav tabs - LISTA lancamento - INI -->
                @if($user->cliente && $user->cliente->tipo != 'AG')
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Efetivo Pecuário
                                @can('create_lancamento')
                                    <i onClick="createLancamento('MG');" class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Novo Lançamento - Efetivo Pecuário"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                @endif
                <!-- Nav tabs - LISTA lancamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lancamento - ATIVA - INI -->
                @if($user->cliente && $user->cliente->tipo != 'AG')
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_lancamentos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th style="text-align:center;">Mês / Ano</th>
                            <th data-toggle="tooltip" title="Total de lançamentos referentes ao Efetivo Pecuário" style="text-align:center;">Lançamentos</th>
                            {{--  <th data-toggle="tooltip" title="Total de lançamentos referentes a movimentação fiscal" style="text-align:center;">Movimentação Fiscal*
                                @can('create_lancamento')
                                    <i onClick="createLancamento('MF');" class="fas fa-plus-circle" style="color: goldenrod; margin-left: 5px; vertical-align: middle;" title="Novo Lançamento - Movimentação Fiscal"></i>
                                @endcan
                            </th>  --}}
                            <th style="text-align:center;">Qtd. Compras</th>
                            <th style="text-align:center;">Qtd. Vendas</th>
                            <th style="text-align:center;">Qtd. Engordas</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <form action="" id="novoLancamento" method="get">
                            @csrf
                            <input type="hidden" id="segmento" name="segmento">
                        </form>

                        <tbody>
                        @forelse($lancamentos as $lancamento)
                        <tr>
                            <td>{{$lancamento->mes_ano}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$lancamento->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$lancamento->total}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$lancamento->compra}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$lancamento->venda}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$lancamento->engorda}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('list_lancamento')
                                <a href="{{route('lancamento.list_MG', ['mes_referencia' => $lancamento->mes_referencia])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Editar o Efetivo Pecuário do mês"></i></a>
                            @endcan

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ ($user->cliente && $user->cliente->tipo != 'AG') ? '5' : '4' }}">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
                </div>
                @endif

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

    @if($lancamentos->count() > 0)
        <script>
            var table = $('#dt_lancamentos').DataTable({
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

    <script>
        function createLancamento(segmento)
        {
            var segmento = segmento;
            var url = '{{ route("lancamento.create") }}';
            document.forms['novoLancamento']['segmento'].value = segmento;
            $("#novoLancamento").attr('action', url);
            $("#novoLancamento").submit();
        }
     </script>


@endsection
