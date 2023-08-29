@extends('painel.layout.index')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><code style="color: #096e48;font-size: 18px;"></code></h4>
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

                <form id="search_report" action="{{route('relatorio_gestao.search')}}" method="GET">
                    @csrf
                    <!-- <input type="hidden" id="mes_referencia" name="mes_referencia" value="">
                    <input type="hidden" id="status_movimentacao" name="status_movimentacao" value=""> -->
                    <span>
                    
                    <div class="row" style="width: 100%;">
                        <div class="col-md-3"  style="padding-right: 0;">
                            <label for="data_inicio" style="margin: 0 0 0 2px;">Data Inicial</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{$search['data_inicio'] ?? ''}}">
                        </div>  
                    
                        <div class="col-md-3"  style="padding-right: 0;">
                            <label for="data_fim" style="margin: 0 0 0 2px;">Data Final</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{$search['data_fim'] ?? ''}}">
                        </div>                          

                        <div class="col-md-4"  style="padding-right: 0;">
                            <label for="item_texto" style="margin: 0 0 0 2px;">Item Fiscal</label>
                            <input type="text" class="form-control" id="item_texto" name="item_texto" value="{{$search['item_texto'] ?? ''}}" placeholder="Item Fiscal">
                        </div>  

                        <div class="col-md-2"  style="padding-right: 0;">
                            <label for="movimentacao" style="margin: 0 0 0 2px;">Movimentação</label>
                            <select id="movimentacao" name="movimentacao" class="form-control">
                                <option value="">---</option>
                                <option value="E" {{($search && $search['movimentacao'] == 'E') ? 'selected' : '' }}>Efetiva</option>
                                <option value="F" {{($search && $search['movimentacao'] == 'F') ? 'selected' : '' }}>Futura</option>
                            </select>
                        </div>                        
                        
                    </div>

                    <div class="row" style="margin-top: 10px;width: 100%;">

                        <div class="col-md-4" style="padding-right: 0;">
                            <select id="cliente" name="cliente" class="form-control select2">
                                <option value="">Selecione: Cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{($search && $search['cliente'] == $cliente->id) ? 'selected' : '' }}>{{ $cliente->nome_cliente }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="tipo_movimentacao" name="tipo_movimentacao" class="form-control select2">
                                <option value="">Selecione: Tipo Movimentação</option>
                                <option value="R" {{($search && $search['tipo_movimentacao'] == 'R') ? 'selected' : '' }}>Receita</option>
                                <option value="D" {{($search && $search['tipo_movimentacao'] == 'D') ? 'selected' : '' }}>Despesa</option>
                            </select>
                        </div>
                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="segmento" name="segmento" class="form-control select2">
                                <option value="">Selecione: Segmento</option>
                                <option value="MG" {{($search && $search['segmento'] == 'MG') ? 'selected' : '' }}>Movimentação Bovina</option>
                                <option value="MF" {{($search && $search['segmento'] == 'MF') ? 'selected' : '' }}>Movimentação Fiscal</option>
                            </select>
                        </div>                        
                    </div>

                    <div class="row" style="margin-top: 10px;width: 100%;">

                        <div class="col-md-4" style="padding-right: 0;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light" style="width:100%;">Filtrar</button>
                        </div>
                    </div>
                    </span>
                </form>

                <div class="titulo-com-icones" style="margin-top: 25px;">
                    <span style="float: right">
                        <!-- <a href="{{route('relatorio.index')}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="relatorio / Movimentação Fiscal do Cliente"></i></a> -->
                        <a href="{{route('relatorio_gestao.excell', compact('search'))}}" style="font-size: 20px;border-right: 1px solid #e1e1e1; margin-right: 5px; padding-right: 5px;"><i class="nav-icon fas fa-file-excel" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="Excell"></i></a>
                        <a href="{{route('relatorio_gestao.pdf', compact('search'))}}" style="font-size: 20px;border-right: 1px solid #e1e1e1; margin-right: 5px; padding-right: 5px;"><i class="nav-icon fas fa-file-pdf" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="PDF"></i></a>
                        <a href="{{route('relatorio_gestao.index')}}"><i class="nav-icon fas fa-sync-alt" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="Limpar pesquisa"></i></a>
                    </span>
                    <h4 class="card-title">Listagem da Movimentação registrada</h4>
                    <p class="card-title-desc"></p>
                </div>    

                <!-- Nav tabs - LISTA lancamento - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#lancamento" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Total ( <code class="highlighter-rouge">{{($movimentacaos) ? $movimentacaos->count() : 0}}</code> )</span>
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
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Segmento</th>
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
                            <td data-toggle="tooltip" title="{{ $movimentacao->cliente->nome_cliente ?? '...' }}">{{ $movimentacao->cliente->nome_cliente_reduzido ?? '...' }}</td>
                            <td>{{$movimentacao->tipo_movimentacao_texto}}</td>
                            <td>{{$movimentacao->segmento_texto}}</td>
                            <td data-toggle="tooltip" title="{{ $movimentacao->item_texto }}">{{$movimentacao->item_texto_reduzido}}</td>
                            <td class="valor_mask">{{$movimentacao->valor}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_programada_formatada}}</td>
                            <td style="text-align:center;">{{$movimentacao->data_pagamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('view_relatorio_gestao')
                                <!-- @if($movimentacao->segmento == 'MF')
                                    <a href="{{route('movimentacao.show', compact('movimentacao'))}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Movimentação Financeira"></i></a>
                                @else
                                    <a href="{{route('efetivo.show', ['efetivo' => $movimentacao->efetivo->id])}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Editar o Efetivo Pecuário"></i></a>
                                @endif -->
                                @if($movimentacao->segmento == 'MF')
                                    <a href="{{route('movimentacao.show', compact('movimentacao'))}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar a Movimentação Financeira"></i></a>
                                @else
                                    <a href="{{route('efetivo.show', ['efetivo' => $movimentacao->efetivo->id])}}" target="_blank"><i class="fa fa-edit" style="color: goldenrod" title="Visualizar o Efetivo Pecuário"></i></a>
                                @endif

                                @if($movimentacao->path_comprovante)
                                <a href="{{ route('movimentacao.download', ['movimentacao' => $movimentacao->id, 'tipo_documento' => 'CP']) }}">
                                    <i class="mdi mdi-currency-usd-circle-outline mdi-18px" style="color: goldenrod;cursor: pointer" title="Download do Comprovante de Pagamento"></i>
                                </a>
                                @endif
                                
                                @if($movimentacao->path_nota)
                                <a href="{{ route('movimentacao.download', ['movimentacao' => $movimentacao->id, 'tipo_documento' => 'NT']) }}">
                                    <i class="fas fa-file-invoice mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da Nota"></i>
                                </a>
                                @endif

                                @if($movimentacao->efetivo && $movimentacao->efetivo->path_gta)
                                <a href="{{ route('efetivo.download', ['efetivo' => $movimentacao->efetivo_id, 'tipo_documento' => 'GT']) }}">
                                    <i class="mdi mdi-file-download mdi-18px" style="color: goldenrod;cursor: pointer" title="Download da GTA"></i>
                                </a>
                                @endif
                            @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">Nenhum registro encontrado</td>
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

    <script>
		$(document).ready(function(){
            $(".valor_mask").inputmask("R$ (.999){+|1},99",{numericInput:true, placeholder:"0"});
            $('.select2').select2();
		});
	</script>

    @if($movimentacaos && $movimentacaos->count() > 0)
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

@endsection
