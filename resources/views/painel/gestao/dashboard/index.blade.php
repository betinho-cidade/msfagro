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

    <form id="search_dashboard" action="{{route('dashboard.index')}}" method="GET">
        @csrf
        <input type="hidden" name="search" id="search" value="BUSCA">
        <div class="row">
            <div class="col-xl-12">        
                <div class="row" style="margin:10px 12px 30px 0 !important; width: 100%;"> 

                    <div class="col-md-4" style="padding-left: 0;">
                        <select id="cliente" name="cliente" class="form-control select2">
                            @if($user->roles->contains('name', 'Gestor'))
                                @foreach($clientes as $cliente_selecao)
                                    <option value="{{ $cliente_selecao->id }}" {{($search &&  $search['cliente'] == $cliente_selecao->id) ? 'selected' : '' }}>{{ $cliente_selecao->nome_cliente}}</option>
                                @endforeach
                                <option value="TODOS" {{($cliente == 'TODOS') ? 'selected' : ''}}>Todos Clientes</option>
                            @else
                                <option value="{{ $clientes->first->get()->id }}" selected>{{ $clientes->first->get()->nome_cliente}}</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4"  style="padding-left: 0;">
                        <select id="efetivo_ano" name="efetivo_ano" class="form-control select2">
                            @foreach($efetivo_anos as $efetivo_ano)
                                <option value="{{ $efetivo_ano['ano'] }}" {{($search && $search['efetivo_ano'] == $efetivo_ano['ano']) ? 'selected' : '' }}>Ano Referência: {{ $efetivo_ano['ano'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4" style="padding-right: 0;">
                        <button type="submit" class="btn btn-primary waves-effect waves-light" style="width:100%;">Filtrar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- INICIO - PARTE 01-->
    <div class="row">
        <div class="col-xl-12">
        <h4 style="text-align:center;margin-bottom:12px;">Resumo Pecuário</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="padding: 10px 20px !important;">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <h4 class="mb-0" style="padding-top: 7px;">Entrada</h4>
                                </div>
                                <div class="text-primary">
                                    <i class="ri-arrow-down-circle-line font-size-24" style="color: #4CAF50;"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top py-3">
                            <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                                <span class="text-muted" style="font-size:15px;">Macho</span>
                                <span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;"> {{$entrada_saida[0]['qtd_entrada_macho'] ?? 0}} </span>	
                            </div>
                            <div class="text-truncate">
                                <span class="text-muted" style="font-size:15px;">Fêmea</span>
                                <span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;"> {{$entrada_saida[0]['qtd_entrada_femea'] ?? 0}} </span>	
                            </div>												
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="padding: 10px 20px !important;">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <h4 class="mb-0" style="padding-top: 7px;">Saída</h4>
                                </div>
                                <div class="text-primary">
                                    <i class="ri-arrow-up-circle-line font-size-24" style="color: #F44336;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-top py-3">
                            <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                                <span class="text-muted" style="font-size:15px;">Macho</span>
                                <span class="badge badge-soft-danger" style="font-size: 14px; padding: 5px 10px !important; float: right;"> {{$entrada_saida[0]['qtd_saida_macho'] ?? 0}} </span>	
                            </div>
                            <div class="text-truncate">
                                <span class="text-muted" style="font-size:15px;">Fêmea</span>
                                <span class="badge badge-soft-danger" style="font-size: 14px; padding: 5px 10px !important; float: right;"> {{$entrada_saida[0]['qtd_saida_femea'] ?? 0}} </span>	
                            </div>	
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body" style="padding: 10px 20px !important;">
                            <div class="media">
                                <div class="media-body overflow-hidden">
                                    <h4 class="mb-0" style="padding-top: 7px;">Estoque Global</h4>
                                </div>
                                <div class="text-primary">
                                    <i class="ri-line-chart-fill font-size-24" style="color:#957c49;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border-top py-3">
                            <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                                <span class="text-muted" style="font-size:15px;">Macho</span>
                                <span class="badge badge-soft-primary" style="font-size: 14px; padding: 5px 10px !important; float: right;color:#957c49;background-color: #f5efe1;"> {{$estoque_global[0]['estoque_macho'] ?? 0}} </span>	
                            </div>
                            <div class="text-truncate">
                                <span class="text-muted" style="font-size:15px;">Fêmea</span>
                                <span class="badge badge-soft-primary" style="font-size: 14px; padding: 5px 10px !important; float: right;color:#957c49;background-color: #f5efe1;"> {{$estoque_global[0]['estoque_femea'] ?? 0}} </span>	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FINAL - PARTE 01-->


    <!-- INICIO - PARTE 02-->
    <div class="row">
     <div class="col-12">
         <div class="card">
             <div class="card-body">

                 <h4 class="card-title" style="padding-top: 5px; margin-bottom: 0; font-size: 16px;">Resumo Financeiro</h4>

                 <div class="table-responsive" style="margin-top:30px;">
				
                     <table class="table table-bordered table-nowrap mb-0">
                         <thead>
                         <tr>
                             <th style="text-align: center;  background: #f0f0f0;">PRODUTOR<br/><small class="badge badge-soft-success" style="font-size: 10px;">Receita</small> <small class="badge badge-soft-danger" style="font-size: 10px;">Despesa</small></th>
                             <th class="text-center">
                                 JAN<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
                             <th class="text-center">
                                 FEV<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
                             <th class="text-center">
                                 MAR<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>														
                             </th>
                             <th class="text-center">
                                 ABR<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>													
                             </th>	
                             <th class="text-center">
                                 MAI<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
                             <th class="text-center">
                                 JUN<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
                         </tr>
                         </thead>
                         <tbody>
                         <tr>
                             <th class="text-nowrap" style="text-align: center;  background: #fbfbfb;">Total Mensal</th>
                             @if(Arr::has($resumo_financeiro, '01'))<td class="text-center" style="color: {{$resumo_financeiro['01']['cor']}};">R$ {{$resumo_financeiro['01']['total']}}</td>@else<td class="text-center">-</td>@endif
                             @if(Arr::has($resumo_financeiro, '02'))<td class="text-center" style="color: {{$resumo_financeiro['02']['cor']}};">R$ {{$resumo_financeiro['02']['total']}}</td>@else<td class="text-center">-</td>@endif
                             @if(Arr::has($resumo_financeiro, '03'))<td class="text-center" style="color: {{$resumo_financeiro['03']['cor']}};">R$ {{$resumo_financeiro['03']['total']}}</td>@else<td class="text-center">-</td>@endif
                             @if(Arr::has($resumo_financeiro, '04'))<td class="text-center" style="color: {{$resumo_financeiro['04']['cor']}};">R$ {{$resumo_financeiro['04']['total']}}</td>@else<td class="text-center">-</td>@endif
                             @if(Arr::has($resumo_financeiro, '05'))<td class="text-center" style="color: {{$resumo_financeiro['05']['cor']}};">R$ {{$resumo_financeiro['05']['total']}}</td>@else<td class="text-center">-</td>@endif
                             @if(Arr::has($resumo_financeiro, '06'))<td class="text-center" style="color: {{$resumo_financeiro['06']['cor']}};">R$ {{$resumo_financeiro['06']['total']}}</td>@else<td class="text-center">-</td>@endif
                         </tr>
                        
                         </tbody>
                     </table>										
		
                 </div>
				
				<div class="table-responsive" style="margin-top:20px;">
				
                     <table class="table table-bordered table-nowrap ">
                         <thead>
                         <tr>                                                    
							<th class="text-center">
                                 JUL<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
							<th class="text-center">
                                 AGO<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
							<th class="text-center">
                                 SET<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
							<th class="text-center">
                                 OUT<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
							<th class="text-center">
                                 NOV<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
							<th class="text-center">
                                 DEZ<br>
								<span style="display: block; margin-top: -5px;"><i class="ri-add-circle-line font-size-12" style="color: #4CAF50;font-weight: 500;"></i> <i class="ri-indeterminate-circle-line font-size-12" style="color: #F44336;font-weight: 500;"></i></span>
                             </th>
                             <th class="text-center" style="vertical-align: middle; background: #f0f0f0;">
                                 TOT. ANUAL
                             </th>															
							  
                         </tr>
                         </thead>
                         <tbody>
                         <tr>                                                    
                            @if(Arr::has($resumo_financeiro, '07'))<td class="text-center" style="color: {{$resumo_financeiro['07']['cor']}};">R$ {{$resumo_financeiro['07']['total']}}</td>@else<td class="text-center">-</td>@endif
                            @if(Arr::has($resumo_financeiro, '08'))<td class="text-center" style="color: {{$resumo_financeiro['08']['cor']}};">R$ {{$resumo_financeiro['08']['total']}}</td>@else<td class="text-center">-</td>@endif
                            @if(Arr::has($resumo_financeiro, '09'))<td class="text-center" style="color: {{$resumo_financeiro['09']['cor']}};">R$ {{$resumo_financeiro['09']['total']}}</td>@else<td class="text-center">-</td>@endif
                            @if(Arr::has($resumo_financeiro, '10'))<td class="text-center" style="color: {{$resumo_financeiro['10']['cor']}};">R$ {{$resumo_financeiro['10']['total']}}</td>@else<td class="text-center">-</td>@endif
                            @if(Arr::has($resumo_financeiro, '11'))<td class="text-center" style="color: {{$resumo_financeiro['11']['cor']}};">R$ {{$resumo_financeiro['11']['total']}}</td>@else<td class="text-center">-</td>@endif
                            @if(Arr::has($resumo_financeiro, '12'))<td class="text-center" style="color: {{$resumo_financeiro['12']['cor']}};">R$ {{$resumo_financeiro['12']['total']}}</td>@else<td class="text-center">-</td>@endif
							<td class="text-center" style="background: #fbfbfb;color:{{$cor_financeiro}}; font-weight: 900;">{{'R$ ' . $total_financeiro ?? '-'}}</td>
                         </tr>
                         
                         </tbody>
                     </table>										
		
                 </div>	
				
             </div>
         </div>
     </div>
</div>
    <!-- FINAL - PARTE 02-->

    <!-- INICIO - PARTE 03-->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="overflow-x: auto;">
                    <h4 class="card-title" style="padding-top: 5px; margin-bottom: 0; font-size: 16px;">Receitas x Despesas</h4>
                    <div class="grafico-receitas-despesas"> 
                        <div id="chart_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FINAL - PARTE 03-->


    <!-- INICIO - PARTE 04 -->

    <div class="row col-xl-12" style="margin: 40px 0 40px 0;   padding: 0;">
    <div class="col-xl-12" style="padding:0;">
	    <h4 style="text-align:center;margin-bottom:12px;">Resumo Tributário</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card" style="min-height: 255px;">
                    <div class="card-body" style="padding: 25px 20px !important;">
                        <div class="media">
                            <div class="media-body overflow-hidden" style="text-align:center;">
								<h4 class="font-size-24" style="color: {{$cor_financeiro}};padding-top: 7px;margin-bottom:2px;">R$ {{($resumo_pecuario['prejuizo'] == 'N') ? $resumo_pecuario['lucro_real'] : ' - ' . $resumo_pecuario['lucro_real']}}</h4>
                                <h5 class="mb-0" style="">Lucro Real</h5>
                                <span class="badge badge-soft-success" style="font-size: 16px;padding: 5px 8px !important;background: #f5efe1;color: #957c49;margin-top: 10px;">Imposto: R$ {{$resumo_pecuario['imposto_real']}}</span>

                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top py-3" style="padding-bottom: 30px !important;">
                        <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">Receitas</span>
							<span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{$resumo_pecuario['total_credito']}}</span>	
                        </div>
                        <div class="text-truncate">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">Despesas</span>
							<span class="badge badge-soft-danger" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{$resumo_pecuario['total_debito']}}</span>	
                        </div>												
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" style="min-height: 255px;">
                    <div class="card-body" style="padding: 25px 20px !important;">
                        <div class="media">
                            <div class="media-body overflow-hidden" style="text-align:center;">
								<h4 class="font-size-24" style="color: green;padding-top: 7px;margin-bottom:2px;">R$ {{$resumo_pecuario['lucro_presumido']}}</h4>
                                <h5 class="mb-0" style="">Lucro Presumido</h5>
                                <span class="badge badge-soft-success" style="font-size: 16px;padding: 5px 8px !important;background: #f5efe1;color: #957c49;margin-top: 10px;">Imposto: R$ {{$resumo_pecuario['imposto_presumido']}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top py-3" style="padding-bottom: 30px !important;">
                        <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">Receitas</span>
							<span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{$resumo_pecuario['total_credito']}}</span>	
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 10px 20px !important;">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <h4 class="mb-0" style="padding-top: 7px;">Qual melhor?</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ri-line-chart-fill font-size-24" style="color: #957c49;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top py-3" style="padding-bottom: 30px !important;padding-top: 30px !important;">
                        <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">Dedução - Lucro Real</span>
							<span class="badge badge-soft-danger" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{($resumo_pecuario['prejuizo'] == 'S') ? ' - ' . $resumo_pecuario['lucro_real'] : 0}}</span>	
                        </div>                       
                        <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">IR - Lucro Real</span>
							<span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{($resumo_pecuario['prejuizo'] == 'N') ? $resumo_pecuario['imposto_real'] : 0}}</span>	
                        </div>
                        <div class="text-truncate" style="padding-bottom: 10px; margin-bottom: 10px; border-bottom: 1px solid #eff2f7;">
                            <span class="text-muted" style="font-size:15px;color: #000 !important;">IR - Lucro Presumido</span>
							<span class="badge badge-soft-success" style="font-size: 14px; padding: 5px 10px !important; float: right;">R$ {{$resumo_pecuario['imposto_presumido']}}</span>	
                        </div>
                    </div>
                </div>
            </div>             -->

        </div>
        <div class="row">
        <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="padding: 10px 20px !important;">
                        <div class="media">
                            <div class="media-body overflow-hidden">
                                <h4 class="mb-0" style="padding-top: 7px;">Qual melhor?</h4>
                            </div>
                            <div class="text-primary">
                                <i class="ri-line-chart-fill font-size-24" style="color: #957c49;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3" style="overflow-x: auto;">
					    <div id="top_x_div"></div>
                    </div>  
                </div>
            </div>
        </div>

    </div>
</div>
<!-- end row -->	
<div class="row" style="margin: 40px 0 80px 0;   padding: 0;text-align: center;display: block;">
    <h3 style="margin-bottom: 20px;"> Ainda tem dúvida? </h3>
    <a href="https://api.whatsapp.com/send/?phone=5571997081850&text=Tenho+interesse+em+um+parecer+jur%C3%ADdico&type=phone_number&app_absent=0" target="_blank"
    style="text-align: center; background: #4CAF50; color: #fff; font-size: 16px; font-weight: 900; line-height: 19px; padding: 12px 37px; border-radius: 30px;">Tenha um parecer com validade Jurídica</a>
</div>
    <!-- FINAL - PARTE 04 -->


@endsection

@section('head-css')
@endsection

@section('script-js')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart','bar'], 'language': 'pt'});
        google.charts.setOnLoadCallback(receita_despesa);
        google.charts.setOnLoadCallback(resumo_tributario);        

        function receita_despesa() {

            var chartDivRD = document.getElementById('chart_div');

            var dataRD = new google.visualization.arrayToDataTable([
            ['', 'Receita', { role: "style" }, 'Despesa', { role: "style" }],
            ['Janeiro',   @if(Arr::has($resumo_financeiro, '01')) {{$resumo_financeiro['01']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '01')) {{$resumo_financeiro['01']['debito']}} @else 0 @endif, "red"],
            ['Fevereiro', @if(Arr::has($resumo_financeiro, '02')) {{$resumo_financeiro['02']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '02')) {{$resumo_financeiro['02']['debito']}} @else 0 @endif, "red"],
            ['Março',     @if(Arr::has($resumo_financeiro, '03')) {{$resumo_financeiro['03']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '03')) {{$resumo_financeiro['03']['debito']}} @else 0 @endif, "red"],
            ['Abril',     @if(Arr::has($resumo_financeiro, '04')) {{$resumo_financeiro['04']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '04')) {{$resumo_financeiro['04']['debito']}} @else 0 @endif, "red"],
            ['Maio',      @if(Arr::has($resumo_financeiro, '05')) {{$resumo_financeiro['05']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '05')) {{$resumo_financeiro['05']['debito']}} @else 0 @endif, "red"],
            ['Junho',     @if(Arr::has($resumo_financeiro, '06')) {{$resumo_financeiro['06']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '06')) {{$resumo_financeiro['06']['debito']}} @else 0 @endif, "red"],
            ['Julho',     @if(Arr::has($resumo_financeiro, '07')) {{$resumo_financeiro['07']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '07')) {{$resumo_financeiro['07']['debito']}} @else 0 @endif, "red"],
            ['Agosto',    @if(Arr::has($resumo_financeiro, '08')) {{$resumo_financeiro['08']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '08')) {{$resumo_financeiro['08']['debito']}} @else 0 @endif, "red"],
            ['Setembro',  @if(Arr::has($resumo_financeiro, '09')) {{$resumo_financeiro['09']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '09')) {{$resumo_financeiro['09']['debito']}} @else 0 @endif, "red"],
            ['Outubro',   @if(Arr::has($resumo_financeiro, '10')) {{$resumo_financeiro['10']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '10')) {{$resumo_financeiro['10']['debito']}} @else 0 @endif, "red"],
            ['Novembro',  @if(Arr::has($resumo_financeiro, '11')) {{$resumo_financeiro['11']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '11')) {{$resumo_financeiro['11']['debito']}} @else 0 @endif, "red"],
            ['Dezembro',  @if(Arr::has($resumo_financeiro, '12')) {{$resumo_financeiro['12']['credito']}} @else 0 @endif, "green", @if(Arr::has($resumo_financeiro, '12')) {{$resumo_financeiro['12']['debito']}} @else 0 @endif, "red"],
            ]);

            var formatterRD = new google.visualization.NumberFormat({
                            decimalSymbol: ',',
                            groupingSymbol: '.',
                            prefix: 'R$ ',
                        });
                        formatterRD.format(dataRD, 1);
                        formatterRD.format(dataRD, 3);

            var viewRD = new google.visualization.DataView(dataRD);

            var optionsRD = {
            chartArea: {
                width: '100%'
            },                
            width: '100%',
            vAxis: { 
            //   title: "Percentage Uptime", 
              minValue: 1,
              viewWindowMode:'explicit',
              viewWindow:{
                min: 1
              }
            },
            legend: { position: 'none' },
            // chart: {
            //     title: '',
            //     subtitle: ''
            // },
            bar: { groupWidth: "65%" },
            };

            var chartRD = new google.visualization.ColumnChart(chartDivRD);
            chartRD.draw(viewRD, optionsRD);

        };

        function resumo_tributario() {
            var chartDivRT = document.getElementById('top_x_div');
            
            var datRT = new google.visualization.arrayToDataTable([
            ["", "", { role: "style" } ],
            ["Dedução - Lucro Real", {{($resumo_pecuario['prejuizo'] == 'S') ? $resumo_pecuario['lucro_real_graph'] : 0}}, "red"],
            ["IR - Lucro Real", {{($resumo_pecuario['prejuizo'] == 'N') ? $resumo_pecuario['imposto_real_graph'] : 0}}, "blue"],
            ["IR - Lucro Presumido", {{$resumo_pecuario['imposto_presumido_graph']}}, "blue"],
            ]);

            var formatterRT = new google.visualization.NumberFormat({
                            decimalSymbol: ',',
                            groupingSymbol: '.',
                            prefix: 'R$ ',
                        });
                        formatterRT.format(datRT, 1);
                        
            var viewRT = new google.visualization.DataView(datRT);
       

            var optionsRT = {
            chartArea: {
                width: '100%'
            },                
            width: '100%',
            vAxis: { 
            //   title: "Percentage Uptime", 
              minValue: 1,
            //   viewWindowMode:'explicit',
            //   viewWindow:{
            //     min: 1
            //   }
            },            
            legend: { position: 'none' },
            chart: {
                title: '',
                subtitle: '' 
            },
            bar: { groupWidth: "30%" }
            };

            var chartRT = new google.visualization.ColumnChart(chartDivRT);
            chartRT.draw(viewRT, optionsRT);

        };           

    </script>

@endsection


