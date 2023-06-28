@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><code style="color: #096e48;font-size: 18px;">{!! $texto_movimentacao !!} - {!! $data_programada !!}</code></h4>
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

                <form id="search_cliente" action="{{route('financeiro.search')}}" method="GET">
                    @csrf
                    <input type="hidden" id="mes_referencia" name="mes_referencia" value="{{ $mes_referencia }}">
                    <input type="hidden" id="status_movimentacao" name="status_movimentacao" value="{{ $status_movimentacao }}">
                    <span>
                    <div class="row" style="width: 100%;">

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="tipo_movimentacao" name="tipo_movimentacao" class="form-control select2">
                                <option value="">Selecione: Tipo Movimentação</option>
                                <option value="R" {{($search && $search['tipo_movimentacao']['param_key'] == 'R') ? 'selected' : '' }}>Receita</option>
                                <option value="D" {{($search && $search['tipo_movimentacao']['param_key'] == 'D') ? 'selected' : '' }}>Despesa</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="padding-right: 0;">
                            <select id="produtor" name="produtor" class="form-control select2">
                                <option value="">Selecione: Produtor</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{($search && $search['produtor']['param_key'] == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="forma_pagamento" name="forma_pagamento" class="form-control select2">
                                <option value="">Selecione: Forma Pagamento</option>
                                @foreach($forma_pagamentos as $forma_pagamento)
                                    <option value="{{ $forma_pagamento->id }}" {{($search && $search['forma_pagamento']['param_key'] == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px;width: 100%;">

                        @if($user->cliente->tipo != 'AG')
                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="segmento" name="segmento" class="form-control select2">
                                <option value="">Selecione: Segmento</option>
                                <option value="MG" {{($search && $search['segmento']['param_key'] == 'MG') ? 'selected' : '' }}>Movimentação Bovina</option>
                                <option value="MF" {{($search && $search['segmento']['param_key'] == 'MF') ? 'selected' : '' }}>Movimentação Fiscal</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="empresa" name="empresa" class="form-control select2">
                                <option value="">Selecione: Empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{($search && $search['empresa']['param_key'] == $empresa->id) ? 'selected' : '' }}>{{ $empresa->nome_empresa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3" style="padding-right: 0;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light" style="width:100%;">Filtrar</button>
                        </div>
                    </div>
                    </span>
                </form>

                <!-- @php $count = 0; @endphp -->
                <span style="font-size:12px;display: block;margin-top: 15px;">
                <!-- @if($search)
                    @foreach ($search as $param=>$value )
                        @if($value)
                            @if($count == 0)
                                <code>Filtro:</code>
                            @endif
                            @if($value['param_key'])
                                <code>[{{ $param }}: {{ $value['param_value'] }}]&nbsp;</code>
                            @endif
                            @php $count = $count + 1; @endphp
                        @endif
                    @endforeach
                    <p></p>
                @endif -->
                </span>

                <span style="float: right">
                    <a href="{{route('financeiro.index')}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Financeiro / Movimentação Fiscal do Cliente"></i></a>
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>
                <h4 class="card-title">Listagem da Movimentação registrada para o Cliente</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA lancamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#lancamento" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">{!! $texto_movimentacao !!} - {!! ucfirst($data_programada) !!} ( <code class="highlighter-rouge">{{$movimentacaos->count()}}</code> )</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA lancamento - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lancamento - RECEITA - INI -->
                <div class="tab-pane active" id="lancamento" role="tabpanel">
                    <table id="dt_lancamentos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Segmento</th>
                            <th>Empresa</th>
                            <th>Item Fiscal</th>
                            <th>Valor</th>
                            <th style="text-align:center;">Programada</th>
                            <th style="text-align:center;">Pagamento</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($movimentacaos as $movimentacao)
                        <tr>
                            <td>{{$movimentacao->data_programada_ordenacao}}</td>
                            <td>{{$movimentacao->id}}</td>
                            <td>{{$movimentacao->tipo_movimentacao_texto}}</td>
                            <td>{{$movimentacao->segmento_texto}}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->empresa->nome_empresa ?? '...' }}">{{ $movimentacao->empresa->nome_empresa_reduzido ?? '...' }}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->item_texto }}">{{$movimentacao->item_texto_reduzido}}</td>
                            <td class="valor_mask">{{$movimentacao->valor}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('edit_movimentacao')
                                @if($movimentacao->segmento == 'MF')
                                    <a href="{{route('movimentacao.show', compact('movimentacao'))}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Movimentação Financeira"></i></a>
                                @else
                                    <a href="{{route('efetivo.show', ['efetivo' => $movimentacao->efetivo->id])}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Efetivo Pecuário"></i></a>
                                @endif
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">Nenhum registro encontrado</td>
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

    <script>
		$(document).ready(function(){
            $(".valor_mask").inputmask("R$ (.999){+|1},99",{numericInput:true, placeholder:"0"});
            $('.select2').select2();
		});
	</script>

    @if($movimentacaos->count() > 0)
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

@endsection
