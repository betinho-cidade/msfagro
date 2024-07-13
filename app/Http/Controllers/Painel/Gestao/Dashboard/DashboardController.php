<?php

namespace App\Http\Controllers\Painel\Gestao\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Efetivo;
use App\Models\Movimentacao;
use App\Models\Fazenda;
use App\Models\Cliente;
use App\Models\Aliquota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;


class DashboardController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_dashboard')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }
        $user = Auth()->User();

        $primeiro_acesso = ($request->has('search') && $request->search == 'BUSCA') ? false : true;

        $clientes = Cliente::where(function($query) use ($user){
                                if($user->roles->contains('name', 'Cliente')){
                                    $query->where('id', $user->cliente_user->cliente->id);
                                }                                 
                             })  
                             ->where('status', 'A')
                             ->orderBy('nome', 'asc')
                             ->get();

        $cliente = 'TODOS';
        if($user->roles->contains('name', 'Gestor')){
            if($primeiro_acesso){
                $cliente = Cliente::where('id', $clientes->first->get()->id)
                            ->first()->id;
            }elseif($request->cliente && $request->cliente != 'TODOS'){
                $cliente = Cliente::where('id', $request->cliente)
                            ->first()->id;
            }
        }elseif($user->roles->contains('name', 'Cliente')){
            $cliente = $user->cliente_user->cliente->id;
        }  
        

        $efetivo_anos = Movimentacao::where(function($query) use ($cliente){
                                        if($cliente !== 'TODOS'){
                                            $query->where('cliente_id', $cliente);
                                        }
                                     })
                                     ->whereIn('situacao', ['PG'])
                                     ->selectRaw("year(data_pagamento) as ano")
                                     ->groupByRaw("year(data_pagamento)") 
                                     ->orderByRaw("year(data_pagamento) desc")
                                     ->get(); 

        if($efetivo_anos->count() == 0){
            $efetivo_anos = collect([
                ['ano' => Carbon::now()->year]
            ]);
        }  

        $search = [
            'efetivo_ano' => $efetivo_anos[0]['ano'],
            'cliente' => $cliente,
        ];

        $entrada_saida = Efetivo::where(function($query) use ($search, $cliente){
                                        if($cliente !== 'TODOS'){
                                            $query->where('cliente_id', $cliente);
                                        }         
                                        
                                        if($search['efetivo_ano']){
                                            $query->whereYear('data_programada', $search['efetivo_ano']);      
                                        } else {
                                            $query->whereYear('data_programada', $efetivo_anos[0]['ano']);      
                                        }
                                   })                                            
                                    ->select(
                                    DB::raw("SUM(CASE WHEN tipo = 'CP' THEN qtd_macho END) AS qtd_entrada_macho"),   
                                    DB::raw("SUM(CASE WHEN tipo = 'CP' THEN qtd_femea END) AS qtd_entrada_femea"),   
                                    DB::raw("SUM(CASE WHEN tipo = 'VD' THEN qtd_macho END) AS qtd_saida_macho"),   
                                    DB::raw("SUM(CASE WHEN tipo = 'VD' THEN qtd_femea END) AS qtd_saida_femea"),   
                                    )
                                    ->where('segmento', 'MG')
                                    ->get();

        $estoque_global = Fazenda::where(function($query) use ($cliente){
                                        if($cliente !== 'TODOS'){
                                            $query->where('cliente_id', $cliente);
                                        }          
                                    })      
                                    ->select(
                                    DB::raw("SUM(qtd_macho) AS estoque_macho"),   
                                    DB::raw("SUM(qtd_femea) AS estoque_femea"),   
                                    )
                                    ->where('status', 'A')
                                    ->get();    


        $lancamentos = Movimentacao::select(
                                        DB::raw("YEAR(data_pagamento) as ano"),   
                                        DB::raw("LPAD(MONTH(data_pagamento),2,0) as mes"),   
                                        DB::raw("SUM(CASE WHEN tipo = 'D' then valor END) as debito"),   
                                        DB::raw("SUM(CASE WHEN tipo = 'R' then valor END) as credito"),   
                                    )                                                     
                                    ->where(function($query) use ($search, $cliente){
                                        if($cliente !== 'TODOS'){
                                            $query->where('cliente_id', $cliente);
                                        }      

                                        if($search['efetivo_ano']){
                                            $query->whereYear('data_pagamento', $search['efetivo_ano']);      
                                        } else {
                                            $query->whereYear('data_pagamento', $efetivo_anos[0]['ano']);      
                                        }                                        
                                    })
                                    ->whereIn('situacao', ['PG'])
                                    ->groupByRaw('YEAR(data_pagamento), MONTH(data_pagamento)')
                                    ->orderByRaw('YEAR(data_pagamento)')
                                    ->orderByRaw('MONTH(data_pagamento)')
                                    ->get();

        $total_credito = 0;
        $total_debito = 0;
        $total_financeiro = 0;
        $resumo_financeiro = [];
        
        foreach($lancamentos as $lancamento){
           
            $resumo_financeiro[$lancamento->mes] = [
                'mes' => $lancamento->mes,
                'debito' => ($lancamento->debito ?? 0),
                'credito' => ($lancamento->credito ?? 0),
                'total' => number_format(($lancamento->credito ?? 0) - ($lancamento->debito ?? 0),2,',','.'),
                'cor' => (($lancamento->credito ?? 0) >= ($lancamento->debito ?? 0)) ? 'green' : 'red'
            ];

            $total_debito = $total_debito + $resumo_financeiro[$lancamento->mes]['debito'];
            $total_credito = $total_credito + $resumo_financeiro[$lancamento->mes]['credito'];
        }

        $total_financeiro = number_format($total_credito - $total_debito,2,',','.');
        $cor_financeiro = ($total_credito >= $total_debito) ? 'green' : 'red';

        $aliquotas = Aliquota::orderBy('base_inicio')
                            ->get();

        $lucro_real = ($total_credito >= $total_debito) ? ($total_credito - $total_debito) : ($total_debito - $total_credito);
        $prejuizo = ($total_credito >= $total_debito) ? 'N' : 'S';
        $lucro_presumido = $total_credito*0.2;
        $imposto_real = 0;
        $imposto_presumido = 0;
        
        foreach($aliquotas as $aliquota){

            if($prejuizo == 'N'){
                if(($lucro_real >= $aliquota->base_inicio) && ($lucro_real <= $aliquota->base_fim || $aliquota->base_fim == 0)){
                    if($aliquota->aliquota > 0){
                        $imposto_real = $lucro_real * ($aliquota->aliquota/100);    
                    }
                    $imposto_real = $imposto_real - $aliquota->parcela_deducao;
                }
            }

            if(($lucro_presumido >= $aliquota->base_inicio) && ($lucro_presumido <= $aliquota->base_fim || $aliquota->base_fim == 0)){
                if($aliquota->aliquota > 0){
                    $imposto_presumido = $lucro_presumido * ($aliquota->aliquota/100);    
                }
                $imposto_presumido = $imposto_presumido - $aliquota->parcela_deducao;                
            }     
        }

        $resumo_pecuario = [
            'total_credito' => number_format($total_credito,2,',','.'),
            'total_debito' => number_format($total_debito,2,',','.'),
            'lucro_real' => number_format($lucro_real,2,',','.'),
            'lucro_real_graph' => ($prejuizo == 'N') ? $lucro_real : $lucro_real * -1,
            'lucro_presumido' => number_format($lucro_presumido,2,',','.'),
            'imposto_real' => number_format($imposto_real,2,',','.'),
            'imposto_real_graph' => $imposto_real,
            'imposto_presumido' => number_format($imposto_presumido,2,',','.'),     
            'imposto_presumido_graph' => $imposto_presumido,     
            'prejuizo' => $prejuizo,       
        ];     

        return view('painel.gestao.dashboard.index', compact('user', 'efetivo_anos', 'clientes', 'cliente', 'search', 'entrada_saida' , 'estoque_global', 'resumo_financeiro', 'total_financeiro', 'cor_financeiro', 'resumo_pecuario'));
    }

}


