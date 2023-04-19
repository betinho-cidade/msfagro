@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Lançamentos do Cliente - {!! $data_criacao !!}</h4>
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

                <h4 class="card-title">Listagem dos lancamentoes Registrados para o Cliente - Referência {!! strtoupper($data_criacao) !!}</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA lancamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#lancamento_MG" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Lançamentos Bovinos ( <code class="highlighter-rouge">{{$lancamentos_MG->count()}}</code> )
                                @can('delete_list_lancamento')
                                    @if($lancamentos_MG->count() > 0)
                                        <i onClick="deleteDataList('MG');" data-toggle="modal" data-target="#modal-delete-lancamento-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos do mês - Movimentação Bovina"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#lancamento_MF" role="tab">
                            <span class="d-block d-sm-none"><i class=" ri-close-circle-line"></i></span>
                            <span class="d-none d-sm-block">Lançamentos Fiscais ( <code class="highlighter-rouge">{{$lancamentos_MF->count()}}</code> )
                                @can('delete_list_lancamento')
                                    @if($lancamentos_MF->count() > 0)
                                        <i onClick="deleteDataList('MF');" data-toggle="modal" data-target="#modal-delete-lancamento-list" class="fa fa-minus-circle" style="color: #dc143c; margin-left: 5px; vertical-align: middle;" title="Excluir os Lançamentos do mês - Movimentação Fiscal"></i>
                                    @endif
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA lancamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lancamento - ATIVA - INI -->
                <div class="tab-pane active" id="lancamento_MG" role="tabpanel">
                    <table id="dt_lancamentos_MG" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Produtor</th>
                            <th>Fazenda</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($lancamentos_MG as $lancamento)
                        <tr>
                            <td>{{$lancamento->id}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->empresa->nome ?? '...' }}">{{Str::limit($lancamento->empresa->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->produtor->nome ?? '...' }}">{{Str::limit($lancamento->produtor->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->fazenda->nome ?? '...' }}">{{Str::limit($lancamento->fazenda->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->categoria->nome ?? '...' }}">{{Str::limit($lancamento->categoria->nome ?? '...', 150, '...')}}</td>
                            <td>{{$lancamento->tipo}}</td>
                            <td>{{$lancamento->data}}</td>
                            <td style="text-align:center;">

                            @can('edit_lancamento')
                                <a href="{{route('lancamento.show', compact('lancamento'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Lançamento"></i></a>
                            @endcan

                            @can('delete_lancamento')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$lancamento->id}})"
                                    data-target="#modal-delete-lancamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Lançamento"></i></a>
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


                <!-- Nav tabs - LISTA lancamento - INATIVA - INI -->
                <div class="tab-pane" id="lancamento_MF" role="tabpanel">
                    <table id="dt_lancamentos_MF" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Produtor</th>
                            <th>Fazenda</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($lancamentos_MF as $lancamento)
                        <tr>
                            <td>{{$lancamento->id}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->empresa->nome ?? '...' }}">{{Str::limit($lancamento->empresa->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->produtor->nome ?? '...' }}">{{Str::limit($lancamento->produtor->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->fazenda->nome ?? '...' }}">{{Str::limit($lancamento->fazenda->nome ?? '...', 150, '...')}}</td>
                            <td data-toggle="tooltip" title="{{ $lancamento->categoria->nome ?? '...' }}">{{Str::limit($lancamento->categoria->nome ?? '...', 150, '...')}}</td>
                            <td>{{$lancamento->tipo}}</td>
                            <td>{{$lancamento->data}}</td>
                            <td style="text-align:center;">

                            @can('edit_lancamento')
                                <a href="{{route('lancamento.show', compact('lancamento'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Lançamento"></i></a>
                            @endcan

                            @can('delete_lancamento')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$lancamento->id}})"
                                    data-target="#modal-delete-lancamento"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir o Lançamento"></i></a>
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
                    <!-- Nav tabs - LISTA lancamento - INATIVA - FIM -->
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
    <input type="hidden" id="segmento" name="segmento">
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
                <p>Todos os registros do mês selecionado serão excluídos definitivamente, BEM COMO TODOS seus relacionamentos. </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Fechar </button>
                <button type="button" onclick="formSubmitList();" class="btn btn-primary waves-effect waves-light">Excluir </button>
            </div>
        </div>
    </div>
</div>
<!-- Cityinbag - Modal Info FIM-->


<form action="" id="deleteForm" method="post">
    @csrf
    @method('DELETE')
    <input type="hidden" id="mes_referencia" name="mes_referencia" value="{{ $mes_referencia }}">
</form>

<div class="modal fade" id="modal-delete-lancamento" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir o registro ? </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>O registro selecionado será excluído definitivamente, BEM COMO TODOS seus relacionamentos. </p>
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

    @if($lancamentos_MG->count() > 0)
        <script>
            var table_AT = $('#dt_lancamentos_MG').DataTable({
                language: {
                    url: '{{asset('nazox/assets/localisation/pt_br.json')}}'
                },
                "order": [[ 1, "desc" ]]
            });
        </script>
    @endif

    @if($lancamentos_MF->count() > 0)
        <script>
            var table_IN = $('#dt_lancamentos_MF').DataTable({
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
           var url = '{{ route("lancamento.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>


    <script>
        function deleteDataList(segmento)
        {
            var segmento = segmento;
            var url = '{{ route("lancamento.list_destroy") }}';
            document.forms['deleteFormList']['segmento'].value = segmento;
            $("#deleteFormList").attr('action', url);
        }

        function formSubmitList()
        {
            $("#deleteFormList").submit();
        }
     </script>

@endsection
