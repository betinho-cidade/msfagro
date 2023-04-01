@extends('painel.layout.index')

@section('content')

@if ($errors->any())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
        </div>
    </div>
@endif

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Dashboard</h4>
                {{--  <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Nazox</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>  --}}
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- TOTAIS POR CATEGORIA - INICIO -->

    <div class="row">
        <div class="col-xl-7">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2"><span class="cor-azul">Ativos</span></p>
                                    <h4 class="mb-0"><span class="cor-azul">{{$categorias['T']}}</span> <span style="font-size:12px;"> associado(s)</span></h4>
                                </div>
                                <div class="text-primary">
                                    <i class="mdi mdi-police-badge-outline font-size-24 cor-azul"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top py-2">
                            <div class="text-truncate">
                                <small class="">{{$mes_atual}}</small><span> - <span class="cor-azul">{{$categorias_mes['T']}}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2"><span class="cor-vermelho">Desligados</span></p>
                                    <h4 class="mb-0"><span class="cor-vermelho">{{$categorias['D']}}</span><span style="font-size:12px;"> associado(s)</span></h4>
                                </div>
                                <div class="text-primary">
                                    <i class="mdi mdi-police-badge-outline font-size-24 cor-azul"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top py-2">
                            <div class="text-truncate">
                                <small class="">{{$mes_atual}}</small><span> - <span class="cor-vermelho">{{$categorias_mes['D']}}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Categoria A</p>
                                    <h4 class="mb-0">{{$categorias['A']}}<span style="font-size:12px;"> associado(s)</span></h4>
                                </div>
                                <div class="text-primary">
                                    <i class="mdi mdi-police-badge-outline font-size-24 cor-azul"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top py-2">
                            <div class="text-truncate">
                                <small class="">{{$mes_atual}}</small><span> - {{$categorias_mes['A']}} novo(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Categoria B</span></p>
                                    <h4 class="mb-0">{{$categorias['B']}}<span style="font-size:12px;"> associado(s)</span></h4>
                                </div>
                                <div class="text-primary">
                                    <i class="mdi mdi-police-badge-outline font-size-24 cor-azul"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top py-2">
                            <div class="text-truncate">
                                <small class="">{{$mes_atual}}</small><span> - {{$categorias_mes['B']}} novo(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>

        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-4">Forma de pagamento (Boleto / Débito Automático)</h4>

                    <div id="piechart" style="text-align: center;">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    <!-- TOTAIS POR CATEGORIA - FIM -->

    <div class="row"><br></div>

    <!-- TOTAIS POR UNIDADE / SETOR E CIDADE - INICIO -->

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <h4 class="card-title titulos-table-dash">Setor de serviço</h4>
                <div class="card-body tabela-dash" style="height: 400px;overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>Setor</th>
                                    <th>qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($setor_servicos as $setor)
                                <tr>
                                  <td>{{$setor->setor_servico}}</td>
                                  <td>{{$setor->qtd_setor}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <h4 class="card-title titulos-table-dash">Unidade de serviço</h4>
                <div class="card-body tabela-dash" style="height: 400px;overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>Unidade</th>
                                    <th>qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidade_servicos as $unidade)
                                <tr>
                                  <td>{{$unidade->unidade_servico}}</td>
                                  <td>{{$unidade->qtd_unidade}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <h4 class="card-title titulos-table-dash">Cidade de serviço</h4>
                <div class="card-body tabela-dash" style="height: 400px;overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>Cidade</th>
                                    <th>qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cidades_servico as $cidade_servico)
                                <tr>
                                  <td>{{$cidade_servico->cidade_servico}}</td>
                                  <td>{{$cidade_servico->qtd_cidade_servico}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <h4 class="card-title titulos-table-dash">Cidade Domicílio</h4>
                <div class="card-body tabela-dash" style="height: 400px;overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>Cidade</th>
                                    <th>qtd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cidades_domicilio as $cidade_domicilio)
                                <tr>
                                  <td>{{$cidade_domicilio->cidade_domicilio}}</td>
                                  <td>{{$cidade_domicilio->qtd_cidade_domicilio}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>


    </div>

    <!-- TOTAIS POR UNIDADE / SETOR E CIDADE - FIM -->

    <div class="row"><br></div>

    <div class="row">
        <div class="col-lg-12">
          <div class="card">
              <h4 class="card-title titulos-table-dash titulos-previsao-dash">Previsão - {{$proximo_mes}} - <span>R$ {{number_format($previsao, 2, ',', '.')}}</span></h4>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 colunas-previsao-dash">
            <div class="card">
                <h4 class="card-title titulos2-previsao-dash">Mensalidade ({{number_format($faturamento_qtde, 0, ',', '.')}}) - <span>R$ {{number_format($faturamento_total, 2, ',', '.')}}</span></h4>
                <div class="card-body tabela-dash">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Cat.</th>
                                    <th>Valor</th>
                                    <th>Filiados</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @forelse($dash_faturamento as $faturamento)
                                      @if($faturamento->situacao == 'S')
                                        <td @if (!$faturamento->valor) class="linha-total-faturamento" @endif>@if ($faturamento->categoria) {{str_replace('Categoria', '', $faturamento->categoria)}} @else -- @endif</td>
                                        <td @if (!$faturamento->valor) class="linha-total-faturamento" @endif>@if ($faturamento->valor) R$ {{number_format($faturamento->valor, 2, ',', '.')}} @else -- @endif</td>
                                        <td @if (!$faturamento->valor) class="linha-total-faturamento" @endif>{{$faturamento->qtd_filiados}}</td>
                                        <td @if (!$faturamento->valor) class="linha-total-faturamento" @endif>R$ {{number_format($faturamento->valor_total, 2, ',', '.')}}</td>
                                      @endif
                                  </tr>

                                  @empty
                                  <tr>
                                      <td colspan="4">Nenhum registro encontrado.</td>
                                  </tr>
                                  @endforelse


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 colunas-previsao-dash">
            <div class="card">
                <h4 class="card-title titulos2-previsao-dash">Carência ({{number_format($carencia_qtde, 0, ',', '.')}}) - <span>{{number_format($carencia_total, 2, ',', '.')}}</span></h4>
                <div class="card-body tabela-dash">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Cat.</th>
                                    <th>Valor</th>
                                    <th>Filiados</th>
                                    <th>Total (3*)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @forelse($dash_carencia as $carencia)
                                    <td @if (!$carencia->valor) class="linha-total-faturamento" @endif>@if ($carencia->categoria) {{str_replace('Categoria', '', $carencia->categoria)}} @else -- @endif</td>
                                    <td @if (!$carencia->valor) class="linha-total-faturamento" @endif>@if ($carencia->valor) R$ {{number_format($carencia->valor, 2, ',', '.')}} @else -- @endif</td>
                                    <td @if (!$carencia->valor) class="linha-total-faturamento" @endif>{{$carencia->qtd_filiados}}</td>
                                    <td @if (!$carencia->valor) class="linha-total-faturamento" @endif>R$ {{number_format($carencia->valor_total, 2, ',', '.')}}</td>
                                </tr>
                                @empty
                                    <td colspan="4">Nenhum registro encontrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4 colunas-previsao-dash">
            <div class="card">
                <h4 class="card-title titulos2-previsao-dash">Taxa ({{number_format($taxa_qtde, 0, ',', '.')}}) - <span>R$ {{number_format($taxa_total, 2, ',', '.')}}</span></h4>
                <div class="card-body tabela-dash">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">

                            <thead>
                                <tr>
                                    <th>Cat.</th>
                                    <th>Valor</th>
                                    <th>Filiados</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @forelse($dash_taxa as $taxa)
                                    <td @if (!$taxa->valor) class="linha-total-faturamento" @endif>@if ($taxa->tipo) {{$taxa->tipo}} @else -- @endif</td>
                                    <td @if (!$taxa->valor) class="linha-total-faturamento" @endif>@if ($taxa->valor) R$ {{number_format($taxa->valor, 2, ',', '.')}} @else -- @endif</td>
                                    <td @if (!$taxa->valor) class="linha-total-faturamento" @endif>{{$taxa->qtd}}</td>
                                    <td @if (!$taxa->valor) class="linha-total-faturamento" @endif>R$ {{number_format($taxa->valor_total, 2, ',', '.')}}</td>
                                  </tr>
                                @empty
                                    <td colspan="4">Nenhum registro encontrado.</td>
                                  </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>


@endsection

@section('head-css')
@endsection


@section('script-js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

    var data = google.visualization.arrayToDataTable(
      {!! json_encode($forma_pagamento) !!}
    );

    var options = {
      {{--  title: 'Forma de Pagamento (Boleto / Débito Automático)',  --}}
      chartArea:{left:20,top:20,width:'95%',height:'95%'}
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
  }
</script>
@endsection


