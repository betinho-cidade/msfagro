@extends('painel.layout.index')


@section('content') 

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Distribuição de Lucros do Cliente</h4>
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

                <div class="filtro-e-relario" style="padding: 0 0 0 30px; margin-bottom: {{($lucros && $lucros->count() > 0) ? 0 : 25}}px;">
                    <form id="search_lucro" action="{{route('lucro.search')}}" method="GET">
                        @csrf
                        <!-- <input type="hidden" id="mes_referencia" name="mes_referencia" value="">
                        <input type="hidden" id="status_movimentacao" name="status_movimentacao" value=""> -->
                        <span>
                        <div class="row" style="width: 100%;display: flow-root;">

                            <div class="row" style="width: {{($lucros && $lucros->count() > 0) ? 50 : 100}}%; float:left">
                                <div class="row" style="width: 100%;">
                                    <div class="col-md-4"  style="padding-right: 0;margin-bottom: 10px;">
                                        <label for="data_inicio" style="margin: 0 0 0 2px;">Data Inicial</label>
                                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{$search['data_inicio'] ?? ''}}">
                                    </div>  
                                
                                    <div class="col-md-4"  style="padding-right: 0;margin-bottom: 10px;">
                                        <label for="data_fim" style="margin: 0 0 0 2px;">Data Final</label>
                                        <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{$search['data_fim'] ?? ''}}">
                                    </div>                          

                                    <div class="col-md-4"  style="padding-right: 0;margin-bottom: 10px;">
                                        <label for="observacao" style="margin: 0 0 0 2px;">Observação</label>
                                        <input type="text" class="form-control" id="observacao" name="observacao" value="{{$search['observacao'] ?? ''}}" placeholder="Observação">
                                    </div>  
                                    
                                    <div class="col-md-4" style="padding-right: 0;margin-bottom: 10px;">
                                        <select id="produtor" name="produtor" class="form-control select2">
                                            <option value="">Selecione: Produtor</option>
                                            @foreach($produtors as $produtor)
                                                <option value="{{ $produtor->id }}" {{($search && $search['produtor'] == $produtor->id) ? 'selected' : '' }}>{{ $produtor->nome_produtor }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4"  style="padding-right: 0;margin-bottom: 10px;">
                                        <select id="forma_pagamento" name="forma_pagamento" class="form-control select2">
                                            <option value="">Selecione: Forma Pagamento</option>
                                            @foreach($forma_pagamentos as $forma_pagamento)
                                                <option value="{{ $forma_pagamento->id }}" {{($search && $search['forma_pagamento'] == $forma_pagamento->id) ? 'selected' : '' }}>{{ $forma_pagamento->forma }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4" style="padding-right: 0;margin-bottom: 10px;">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light" style="width:100%;">Filtrar</button>
                                    </div>

                                </div>

                            </div>

                            @if($lucros && $lucros->count() > 0)
                            <div class="row" style="width: 50%; padding-left: 35px;display: inline-grid;">
                                <div id="piechart" style="margin-top: 5px; width:600px;"></div>
                            </div>
                            @endif
                        </div>
                        </span>
                    </form>
                </div>

                <span style="float: right">
                    <a href="{{route('painel')}}"><i class="nav-icon fas fa-home" style="color: goldenrod; font-size: 14px;margin-right: 4px;" title="Home"></i></a>
                </span>
                <h4 class="card-title">Listagem da Distribuição de Lucro registrada para o Cliente</h4>
                <p class="card-title-desc"></p>

                <!-- Nav tabs - LISTA lucro - INI -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#lucro" role="tab">
                            <span class="d-block d-sm-none"><i class="ri-checkbox-circle-line"></i></span>
                            <span class="d-none d-sm-block">Distribuições de Lucros ( <code class="highlighter-rouge">{{($lucros) ? $lucros->count() : 0}}</code> )
                                @can('create_lucro')
                                    <i class="fas fa-plus-circle" onclick="location.href='{{route('lucro.create')}}'" style="color: goldenrod" title="Nova Distribuição de Lucro"></i>
                                @endcan
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- Nav tabs - LISTA lucro - FIM -->

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                <!-- Nav tabs - LISTA lucro - RECEITA - INI -->
                <div class="tab-pane active" id="lucro" role="tabpanel">
                    <table id="dt_lucros" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ordenação</th>
                            <th>ID</th>
                            <th>Produtor</th>
                            <th>Valor</th>
                            <th>Observação</th>
                            <th style="text-align:center;">Data</th>
                            <th style="text-align:center;">Ações</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($lucros as $lucro)
                        <tr>
                            <td>{{$lucro->data_lancamento_ordenacao}}</td>
                            <td>{{$lucro->id}}</td>
                            <td data-toggle="tooltip" title="{{ $lucro->produtor->nome_produtor ?? '...' }}">{{ $lucro->produtor->nome_produtor_reduzido ?? '...' }}</td>
                            <td class="valor_mask">{{$lucro->valor}}</td>                            
                            <td data-toggle="tooltip" title="{{ $lucro->observacao }}">{{$lucro->observacao_reduzida}}</td>
                            <td style="text-align:center;">{{$lucro->data_lancamento_formatada}}</td>
                            <td style="text-align:center;">

                            @can('edit_lucro')
                                <a href="{{route('lucro.show', compact('lucro'))}}"><i class="fa fa-edit" style="color: goldenrod" title="Editar a Distribuição de Lucro"></i></a>
                            @endcan

                            @can('delete_lucro')
                                <a href="javascript:;" data-toggle="modal" onclick="deleteData({{$lucro->id}})"
                                    data-target="#modal-delete-lucro"><i class="fa fa-minus-circle" style="color: crimson" title="Excluir a Distribuição de Lucro"></i></a>
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
                    <!-- Nav tabs - LISTA lucro - ATIVA - FIM -->
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

<div class="modal fade" id="modal-delete-lucro" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deseja excluir o registro da Distribuição de Lucro ? </h5>
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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable(
            {!! json_encode($lucro_produtors) !!}
            );

            var options = {
                title: 'Distribuição Lucro x Produtor',
                pieHole: 0.8,
                chartArea:{left:20,top:20,width:'110%',height:'100%'},
                is3D:true,
                fontSize:12,
                //pieSliceText: 'value',
                legend:{position: 'labeled',alignment:'center', textStyle:{fontSize:10,top:5}},
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>    

    <script>
		$(document).ready(function(){
            $(".valor_mask").inputmask("R$ (.999){+|1},99",{numericInput:true, placeholder:"0"});
		});
	</script>

    @if($lucros && $lucros->count() > 0)
        <script>
            var table_R = $('#dt_lucros').DataTable({
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
           var url = '{{ route("lucro.destroy", ":id") }}';
           url = url.replace(':id', id);
           $("#deleteForm").attr('action', url);
       }

       function formSubmit()
       {
           $("#deleteForm").submit();
       }
    </script>


@endsection
