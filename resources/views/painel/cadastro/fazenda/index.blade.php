@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Fazendas do Cliente</h4>
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

                <h4 class="card-title">Listagem das Fazendas Registradas para o Cliente 
                    <span style="float:right;"><code>Limite Mensal:  Maps({{$cliente_googlemap->qtd_apimaps ?? 0}} de {{$qtd_apimaps}})  Lat/Long({{$cliente_googlemap->qtd_geolocation ?? 0}} de {{$qtd_geolocation}})</code></span></h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA fazenda - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#ativa" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Fazendas Ativas ( <code class="highlighter-rouge">{{$fazendas_AT->count()}}</code> )
                                @can('create_fazenda')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('fazenda.create')}}'" style="color: goldenrod" title="Nova Fazenda"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#inativa" role="tab">
                            <span class="d-block d-sm-none"><i class=" ri-close-circle-line"></i></span>
                            <span class="d-none d-sm-block">Fazendas Inativas ( <code class="highlighter-rouge">{{$fazendas_IN->count()}}</code> )</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA fazenda - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA fazenda - ATIVA - INI -->
                <div class="tab-pane active" id="ativa" role="tabpanel">
                    <table id="dt_fazendas_AT" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cidade/UF</th>
                            <th>Latitude</th>
                            <th>Longitude</th>

                            @if($user->cliente && $user->cliente->tipo != 'AG')
                            <th>Qtd. Machos</th>
                            <th>Qtd. Fêmeas</th>
                            @endif

                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($fazendas_AT as $fazenda)
                        <tr>
                            <td>{{$fazenda->id}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->nome }}">{{$fazenda->nome_reduzido}}</td>
                            <td>{{$fazenda->endereco}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->latitude }}">{{Str::limit($fazenda->latitude, 50, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->longitude }}">{{Str::limit($fazenda->longitude, 50, '...')}}</td>

                            @if($user->cliente && $user->cliente->tipo != 'AG')
                            <td>{{$fazenda->qtd_macho}}</td>
                            <td>{{$fazenda->qtd_femea}}</td>
                            @endif

                            <td style="text-align:center;">

                            @can('edit_fazenda')
                                <a href="{{route('fazenda.show', compact('fazenda'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Fazenda"></i></a>
                                <i onclick="geoMapsData({{$fazenda->id}})" class="fas fa-map-marker-alt" style="color: goldenrod" data-toggle="tooltip" title="Atualizar a Geolocalização (Latitude/Longitude)"></i>
                            @endcan

                            @can('delete_fazenda')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$fazenda->id}})"
                                    data-target="#modal-delete-fazenda"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Fazenda"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-fazenda"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ ($user->cliente && $user->cliente->tipo != 'AG') ? '8' : '6' }}">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA fazenda - ATIVA - FIM -->
                </div>


                <!-- Nav tabs - LISTA fazenda - INATIVA - INI -->
                <div class="tab-pane" id="inativa" role="tabpanel">
                    <table id="dt_fazendas_IN" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Cidade/UF</th>
                            <th>Latitude</th>
                            <th>Longitude</th>

                            @if($user->cliente && $user->cliente->tipo != 'AG')
                            <th>Qtd. Machos</th>
                            <th>Qtd. Fêmeas</th>
                            @endif

                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($fazendas_IN as $fazenda)
                        <tr>
                            <td>{{$fazenda->id}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->nome }}">{{$fazenda->nome_reduzido}}</td>
                            <td>{{$fazenda->endereco}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->latitude }}">{{Str::limit($fazenda->latitude, 50, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $fazenda->longitude }}">{{Str::limit($fazenda->longitude, 50, '...')}}</td>

                            @if($user->cliente && $user->cliente->tipo != 'AG')
                            <td>{{$fazenda->qtd_macho}}</td>
                            <td>{{$fazenda->qtd_femea}}</td>
                            @endif

                            <td style="text-align:center;">

                            @can('edit_fazenda')
                                <a href="{{route('fazenda.show', compact('fazenda'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Fazenda"></i></a>
                            @endcan

                            @can('delete_fazenda')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$fazenda->id}})"
                                    data-target="#modal-delete-fazenda"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Fazenda"></i></a>
                                    <form action="" id="deleteForm" method="post">
                                    @csrf
                                    @method('DELETE')
                                    </form>
                                    @section('modal_target')"formSubmit();"@endsection
                                    @section('modal_type')@endsection
                                    @section('modal_name')"modal-delete-fazenda"@endsection
                                    @section('modal_msg_title')Deseja excluir o registro ? @endsection
                                    @section('modal_msg_description')O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. @endsection
                                    @section('modal_close')Fechar @endsection
                                    @section('modal_save')Excluir @endsection
                            @endcan
                            </td>
                          </tr>
                        @empty
                        <tr>
                            <td colspan="{{ ($user->cliente && $user->cliente->tipo != 'AG') ? '8' : '6' }}">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA fazenda - INATIVA - FIM -->
                </div>
            </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<form action="" id="geoMapsForm" method="post">
@csrf
</form>    

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

    @if($fazendas_AT->count() > 0)
        <script>
            var table_AT = $('#dt_fazendas_AT').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    @if($fazendas_IN->count() > 0)
        <script>
            var table_IN = $('#dt_fazendas_IN').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    <script>
       function deleteData(id)
       {
           var id = id;
           var url = '{{ route("fazenda.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }

       function geoMapsData(id)
       {
        console.log(id);
           var id = id;
           var url = '{{ route("fazenda.geomaps", ":id") }}';
           url = url.replace(':id', id);
           $("#geoMapsForm").attr('action', url);
           $("#geoMapsForm").submit();
       }
    </script>

@endsection
