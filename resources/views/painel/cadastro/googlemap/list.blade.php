@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><code style="color: #096e48;font-size: 18px;">Visualizações e busca por Latitude/Longitude - {!! $data_programada !!}</code></h4>
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

                <span style="float: right">
                    <a href="{{route('googlemap.index')}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Resumo Mensal"></i></a>
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>
                <h4 class="card-title">Listagem das visualizações e buscas por Latitude/Longitude no mês de referência informado</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA lancamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#mesref" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Visualizações e buscas Lat/Long</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA lancamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lancamento - RECEITA - INI -->
                <div class="tab-pane active" id="mesref" role="tabpanel">
                    <table id="dt_mesref" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Cliente</th>
                            <th style="text-align:center;vertical-align: middle">Qtd. Maps</th>
                            <th style="text-align:center;vertical-align: middle">Qtd. Lat/Long</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($cliente_googlemaps as $cliente_googlemap)
                        <tr>
                            <td>{{$cliente_googlemap->cliente->nome_cliente}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$cliente_googlemap->qtd_apimaps}}</td>
                            <td style="text-align:center;vertical-align: middle">{{$cliente_googlemap->qtd_geolocation}}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
                </div>
            </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('head-css')
    <link href="{{asset('nazox/assets/libs/select2/css/select2.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
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

    <script src="{{asset('nazox/assets/libs/select2/js/select2.min.js')}}"></script>

    @if($cliente_googlemaps->count() > 0)
        <script>
            var table = $('#dt_mesref').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]],
            });
        </script>
    @endif

@endsection
