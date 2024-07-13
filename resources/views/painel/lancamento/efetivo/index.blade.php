@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamentos do Cliente - Efetivo Pecuário - {!! $data_programada !!}</h4>
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
                    <a href="{{route('lancamento.index', ['aba' => 'EP'])}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Lançamentos: Efetivos Pecuários"></i></a>
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>
                <h4 class="card-title">Listagem do Efetivo Pecuário registrado para o Cliente - Referência {!! strtoupper($data_programada) !!}</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA efetivo - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#efetivo_CP" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Compra ( <code class="highlighter-rouge">{{$efetivos->where('tipo', 'CP')->count()}}</code> )
                                @can('delete_list_efetivo')
                                    @if($efetivos->where('tipo', 'CP')->count() > 0)
                                        <i onClick="deleteDataList('CP');" data-toggle="modal" data-target="#modal-delete-efetivo-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos de COMPRA do mês - Efetivo Pecuário"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#efetivo_VD" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Venda ( <code class="highlighter-rouge">{{$efetivos->where('tipo', 'VD')->count()}}</code> )
                                @can('delete_list_efetivo')
                                    @if($efetivos->where('tipo', 'VD')->count() > 0)
                                        <i onClick="deleteDataList('VD');" data-toggle="modal" data-target="#modal-delete-efetivo-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos de VENDA do mês - Efetivo Pecuário"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#efetivo_EG" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Engorda ( <code class="highlighter-rouge">{{$efetivos->where('tipo', 'EG')->count()}}</code> )
                                @can('delete_list_efetivo')
                                    @if($efetivos->where('tipo', 'EG')->count() > 0)
                                        <i onClick="deleteDataList('EG');" data-toggle="modal" data-target="#modal-delete-efetivo-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos de ENGORDA do mês - Efetivo Pecuário"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA efetivo - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA efetivo - COMPRA - INI -->
                <div class="tab-pane active" id="efetivo_CP" role="tabpanel">
                    <table id="dt_efetivos_CP" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Origem/Empresa</th>
                            <th>Destino/Fazenda</th>
                            <th>Categoria</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Pagamento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($efetivos->where('tipo', 'CP') as $efetivo)
                        <tr>
                            <td>{{$efetivo->data_programada_ordenacao}}</td>
                            <td>{{$efetivo->id}}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->empresa->nome_empresa ?? '...' }}">{{ $efetivo->empresa->nome_empresa_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->destino->nome_fazenda ?? '...' }}">{{$efetivo->destino->nome_fazenda_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->categoria->nome ?? '...' }}">{{ $efetivo->categoria->nome_reduzido ?? '...' }} ({{ $efetivo->total_bovinos }})</td>
                            <td style="text-align:center;">{{$efetivo->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$efetivo->movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('view_efetivo')
                                <a href="{{route('efetivo.show', compact('efetivo'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Efetivo Pecuário"></i></a>
                            @endcan

                            @can('delete_efetivo')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$efetivo->id}})"
                                    data-target="#modal-delete-efetivo"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Efetivo Pecuário"></i></a>
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>

                <!-- Nav tabs - LISTA efetivo - VENDA - INI -->
                <div class="tab-pane" id="efetivo_VD" role="tabpanel">
                    <table id="dt_efetivos_VD" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Origem/Fazenda</th>
                            <th>Destino/Empresa</th>
                            <th>Categoria</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Pagamento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($efetivos->where('tipo', 'VD') as $efetivo)
                        <tr>
                            <td>{{$efetivo->data_programada_ordenacao}}</td>
                            <td>{{$efetivo->id}}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->origem->nome_fazenda ?? '...' }}">{{$efetivo->origem->nome_fazenda_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->empresa->nome_empresa ?? '...' }}">{{ $efetivo->empresa->nome_empresa_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->categoria->nome ?? '...' }}">{{ $efetivo->categoria->nome_reduzido ?? '...' }} ({{ $efetivo->total_bovinos }})</td>
                            <td style="text-align:center;">{{$efetivo->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$efetivo->movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('view_efetivo')
                                <a href="{{route('efetivo.show', compact('efetivo'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Efetivo Pecuário"></i></a>
                            @endcan

                            @can('delete_efetivo')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$efetivo->id}})"
                                    data-target="#modal-delete-efetivo"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Efetivo Pecuário"></i></a>
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">Nenhum registro encontrado</td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA efetivo - ATIVA - FIM -->
                </div>

                <!-- Nav tabs - LISTA efetivo - ENGORDA - INI -->
                <div class="tab-pane" id="efetivo_EG" role="tabpanel">
                    <table id="dt_efetivos_EG" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Origem/Fazenda</th>
                            <th>Destino/Fazenda</th>
                            <th>Categoria</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($efetivos->where('tipo', 'EG') as $efetivo)
                        <tr>
                            <td>{{$efetivo->data_programada_ordenacao}}</td>
                            <td>{{$efetivo->id}}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->origem->nome_fazenda ?? '...' }}">{{$efetivo->origem->nome_fazenda_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->destino->nome_fazenda ?? '...' }}">{{$efetivo->destino->nome_fazenda_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $efetivo->categoria->nome ?? '...' }}">{{ $efetivo->categoria->nome_reduzido ?? '...' }} ({{ $efetivo->total_bovinos }})</td>
                            <td style="text-align:center;">{{$efetivo->data_programada_formatada}}</td>
                            <td style="text-align:center;">

                            @can('view_efetivo')
                                <a href="{{route('efetivo.show', compact('efetivo'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Efetivo Pecuário"></i></a>
                            @endcan

                            @can('delete_efetivo')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$efetivo->id}})"
                                    data-target="#modal-delete-efetivo"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Efetivo Pecuário"></i></a>
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

            </div>


            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->


<!-- Cityinbag - Modal Info INI-->
<form action="" id="deleteFormList" method="post">
    @csrf
    @method('POST')
    <input type="hidden" id="tipo" name="tipo">
    <input type="hidden" id="mes_referencia" name="mes_referencia" value="{{ $mes_referencia }}">
</form>

<div class="modal fade" id="modal-delete-efetivo-list" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir os lançamentos do mês selecionado ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Todos os registros do mês selecionado serão excluídos definitivamente. Também serão excluídas suas movimentações fiscais de compra/venda, bem como as operações de atualização de estoque realizadas anterioremente nas respectivas Fazendas, serão desfeitas. Deseja Continuar ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Fechar </button>
                <button type="button" onclick="formSubmitList();" class="btn btn-primary waves-effect waves-light">Excluir </button>
            </div>
        </div>
    </div>
</div>
<!-- Cityinbag - Modal Info FIM-->


<!-- Cityinbag - Modal Info INI-->
<form action="" id="deleteForm" method="post">
    @csrf
    @method('DELETE')
</form>

<div class="modal fade" id="modal-delete-efetivo" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir o registro de Efetivo Pecuário ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>O registro selecionado será excluído definitivamente. Também será excluída a movimentação fiscal de compra/venda, bem como as operações de atualização de estoque realizadas anterioremente nas respectivas Fazendas, serão desfeitas. Deseja Continuar ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Fechar </button>
                <button type="button" onclick="formSubmit();" class="btn btn-primary waves-effect waves-light">Excluir </button>
            </div>
        </div>
    </div>
</div>
<!-- Cityinbag - Modal Info FIM-->

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
		$(document).ready(function(){
            $('.mask_cpf').inputmask('999.999.999-99');
            $('.mask_cnpj').inputmask('99.999.999/9999-99');
            $('.mask_telefone').inputmask('(99) 99999-9999');
            $('.select2').select2();
		});
	</script>

    @if($efetivos->where('tipo', 'CP')->count() > 0)
        <script>
            var table_CP = $('#dt_efetivos_CP').DataTable({
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

    @if($efetivos->where('tipo', 'VD')->count() > 0)
        <script>
            var table_VD = $('#dt_efetivos_VD').DataTable({
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


    @if($efetivos->where('tipo', 'EG')->count() > 0)
        <script>
            var table_EG = $('#dt_efetivos_EG').DataTable({
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
       function deleteData(id)
       {
           var id = id;
           var url = '{{ route("efetivo.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>


    <script>
        function deleteDataList(tipo)
        {
            var tipo = tipo;
            var url = '{{ route("efetivo.destroy_list") }}';
            document.forms['deleteFormList']['tipo'].value = tipo;
            $("#deleteFormList").attr('action', url);
        }

        function formSubmitList()
        {
            $("#deleteFormList").submit();
        }
     </script>

@endsection
