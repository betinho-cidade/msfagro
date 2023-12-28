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

                <form id="search_report" action="{{route('relatorio.search')}}" method="GET">
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

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="tipo_movimentacao" name="tipo_movimentacao" class="form-control select2">
                                <option value="">Selecione: Tipo Movimentação</option>
                                <option value="R" {{($search && $search['tipo_movimentacao'] == 'R') ? 'selected' : '' }}>Receita</option>
                                <option value="D" {{($search && $search['tipo_movimentacao'] == 'D') ? 'selected' : '' }}>Despesa</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="padding-right: 0;">
                            <select id="produtor" name="produtor" class="form-control select2">
                                <option value="">Selecione: Produtor</option>
                                @foreach($produtors as $produtor)
                                    <option value="{{ $produtor->id }}" {{($search && $search['produtor'] == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="forma_pagamento" name="forma_pagamento" class="form-control select2">
                                <option value="">Selecione: Forma Pagamento</option>
                                @foreach($forma_pagamentos as $forma_pagamento)
                                    <option value="{{ $forma_pagamento->id }}" {{($search && $search['forma_pagamento'] == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 10px;width: 100%;">

                        @if($user->cliente->tipo != 'AG')
                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="segmento" name="segmento" class="form-control select2">
                                <option value="">Selecione: Segmento</option>
                                <option value="MG" {{($search && $search['segmento'] == 'MG') ? 'selected' : '' }}>Movimentação Bovina</option>
                                <option value="MF" {{($search && $search['segmento'] == 'MF') ? 'selected' : '' }}>Movimentação Fiscal</option>
                            </select>
                        </div>
                        @endif

                        <div class="col-md-4"  style="padding-right: 0;">
                            <select id="empresa" name="empresa" class="form-control select2">
                                <option value="">Selecione: Empresa</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{($search && $search['empresa'] == $empresa->id) ? 'selected' : '' }}>{{ $empresa->nome_empresa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4" style="padding-right: 0;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light" style="width:100%;">Filtrar</button>
                        </div>
                    </div>
                    </span>
                </form>

                <div class="titulo-com-icones" style="margin-top: 25px;">
                    <div class="exportar-titulo-e-icones" style="padding-bottom: 10px; margin-top: -10px;">
                        <span style="float: right">
                            <span style="display: block;">Exportar para</span>
                        <!-- <a href="{{route('relatorio.index')}}"><i class="nav-icon fas fa-arrow-left" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="relatorio / Movimentação Fiscal do Cliente"></i></a> -->
                        <a href="{{route('relatorio.excell', compact('search'))}}" style="font-size: 20px;border-right: 1px solid #e1e1e1; margin-right: 5px; padding-right: 5px;"><i class="nav-icon fas fa-file-excel" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="Excell"></i></a>
                        <a href="{{route('relatorio.pdf', compact('search'))}}" style="font-size: 20px;border-right: 1px solid #e1e1e1; margin-right: 5px; padding-right: 5px;"><i class="nav-icon fas fa-file-pdf" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="PDF"></i></a>
                        <a href="{{route('painel')}}"><i class="nav-icon fas fa-sync-alt" style="color: goldenrod; font-size: 20px;margin-right: 4px;" title="Limpar pesquisa"></i></a>
                        </span>
                    </div>
                    <h4 class="card-title">Listagem da Movimentação registrada para o Cliente</h4>
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
                            <th>Tipo</th>
                            <th>Segmento</th>
                            <th>Empresa</th>
                            <th>Item Fiscal</th>
                            <th style="text-align:right;">Valor (R$)</th>
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
                            <td style="text-align:right;">{{number_format($movimentacao->valor,2,',','.')}}</td>
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
