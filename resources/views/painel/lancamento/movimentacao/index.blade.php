@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamentos do Cliente - Movimentação Fiscal - {!! $data_programada !!}</h4>
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

                <h4 class="card-title">Listagem da Movimentação Fiscal registrada para o Cliente - Referência {!! strtoupper($data_programada) !!}</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA lancamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#lancamento_R" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Receita ( <code class="highlighter-rouge">{{$movimentacaos->where('tipo', 'R')->count()}}</code> )
                                @can('delete_list_movimentacao')
                                    @if($movimentacaos->where('tipo', 'R')->count() > 0)
                                        <i onClick="deleteDataList('R');" data-toggle="modal" data-target="#modal-delete-lancamento-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos de RECEITA do mês - Movimentação Fiscal"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#lancamento_D" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Despesa ( <code class="highlighter-rouge">{{$movimentacaos->where('tipo', 'D')->count()}}</code> )
                                @can('delete_list_movimentacao')
                                    @if($movimentacaos->where('tipo', 'D')->count() > 0)
                                        <i onClick="deleteDataList('D');" data-toggle="modal" data-target="#modal-delete-lancamento-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos de DESPESA do mês - Movimentação Fiscal"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA lancamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lancamento - RECEITA - INI -->
                <div class="tab-pane active" id="lancamento_R" role="tabpanel">
                    <table id="dt_lancamentos_R" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Item Fiscal</th>
                            <th>Valor</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Pagamento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacaos->where('tipo', 'R') as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td>{{$movimentacao->id}}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->empresa->nome_empresa ?? '...' }}">{{ $movimentacao->empresa->nome_empresa_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->item_texto }}">{{$movimentacao->item_texto_reduzido}}</td>
                            <td class="valor_mask">{{$movimentacao->valor}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('edit_movimentacao')
                                <a href="{{route('movimentacao.show', compact('movimentacao'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Movimentação Fiscal"></i></a>
                            @endcan

                            @can('delete_movimentacao')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$movimentacao->id}})"
                                    data-target="#modal-delete-lancamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Movimentação Fiscal"></i></a>
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
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
                </div>

                <!-- Nav tabs - LISTA lancamento - DESPESA - INI -->
                <div class="tab-pane" id="lancamento_D" role="tabpanel">
                    <table id="dt_lancamentos_D" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Item Fiscal</th>
                            <th>Valor</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Pagamento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacaos->where('tipo', 'D') as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td>{{$movimentacao->id}}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->empresa->nome_empresa ?? '...' }}">{{ $movimentacao->empresa->nome_empresa_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->item_texto }}">{{$movimentacao->item_texto_reduzido}}</td>
                            <td class="valor_mask">{{$movimentacao->valor}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('edit_movimentacao')
                                <a href="{{route('movimentacao.show', compact('movimentacao'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Movimentação Fiscal"></i></a>
                            @endcan

                            @can('delete_movimentacao')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$movimentacao->id}})"
                                    data-target="#modal-delete-lancamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Movimentação Fiscal"></i></a>
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
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
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

<div class="modal fade" id="modal-delete-lancamento-list" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir os lançamentos do mês selecionado ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Todos os registros do mês selecionado serão excluídos definitivamente. Deseja Continuar ?</p>
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

<div class="modal fade" id="modal-delete-lancamento" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir o registro da Movimentação Fiscal ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>O registro selecionado será excluído definitivamente. Deseja Continuar ?</p>
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
            $(".valor_mask").inputmask("R$ (.999){+|1},99",{numericInput:true, placeholder:"0"});
		});
	</script>

    @if($movimentacaos->where('tipo', 'R')->count() > 0)
        <script>
            var table_R = $('#dt_lancamentos_R').DataTable({
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

    @if($movimentacaos->where('tipo', 'D')->count() > 0)
        <script>
            var table_D = $('#dt_lancamentos_D').DataTable({
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
           var url = '{{ route("movimentacao.destroy", ":id") }}';
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
            var url = '{{ route("movimentacao.destroy_list") }}';
            document.forms['deleteFormList']['tipo'].value = tipo;
            $("#deleteFormList").attr('action', url);
        }

        function formSubmitList()
        {
            $("#deleteFormList").submit();
        }
     </script>

@endsection
