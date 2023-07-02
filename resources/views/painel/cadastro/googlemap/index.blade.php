@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Resumo Mensal das visualizações do Mapa e buscas por Latitude e Longitude</h4>
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

                <h4 class="card-title">Resumo Mensal</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA efetivo - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#global" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Visualizações do Mapa e Buscas Lat/Long</span>
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
                            <th  data-toggle="tooltip" title="Total de visualizações do mapa pelos clientes da MFSAgro, no mês de referência" style="text-align:center;">Qtd. Maps</th>
                            <th data-toggle="tooltip" title="Total de buscas pela Latitude/Longitude dos clientes da MFSAgro, no mês de referência" style="text-align:center;">Qtd. Lat/Long</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($cliente_googlemaps as $cliente_googlemap)
                        <tr>
                            <td>{{$cliente_googlemap->mes_ordem}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$cliente_googlemap->mes_referencia}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$cliente_googlemap->qtd_apimaps}}</td>
                            <td style="text-align:center;vertical-align: middle" class="valor_mask">{{$cliente_googlemap->qtd_geolocation}}</td>
                            <td style="text-align:center;vertical-align: middle">

                            @can('view_googlemap')
                            <a href="{{route('googlemap.list', ['mes_referencia' => $cliente_googlemap->mes_referencia])}}"><i class="fas fa-align-justify" style="color: goldenrod" title="Visualizar as informações por cliente, no mês de referência"></i></a>
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


    @if($cliente_googlemaps->count() > 0)
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

@endsection
