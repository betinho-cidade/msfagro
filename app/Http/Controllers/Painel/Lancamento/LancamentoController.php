<?php

namespace App\Http\Controllers\Painel\Lancamento;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Efetivo;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;



class LancamentoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(Gate::denies('view_lancamento')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $aba = ($request->has('aba') ? $request->aba : 'MF');

        if($user->cliente_user->cliente->tipo != 'AG'){
            $efetivos = Efetivo::leftJoin('movimentacaos', 'movimentacaos.efetivo_id', '=', 'efetivos.id')
                                        ->where('efetivos.cliente_id', $user->cliente_user->cliente->id)
                                        ->where('efetivos.segmento', 'MG')
                                        ->groupBy(DB::raw('
                                                        CASE WHEN efetivos.tipo in (\'EG\')
                                                            THEN concat(LPAD(MONTH(efetivos.data_programada), 2, 0), \'-\', YEAR(efetivos.data_programada)) 
                                                            ELSE concat(LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)), 2, 0), \'-\', YEAR(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada))) 
                                                        END'))
                                        ->select(DB::raw('
                                                        CASE WHEN efetivos.tipo in (\'EG\')
                                                            THEN concat(YEAR(efetivos.data_programada), LPAD(MONTH(efetivos.data_programada), 2, 0)) 
                                                            ELSE concat(YEAR(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)), LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)), 2, 0)) 
                                                        END AS mes_ordem, 
                                                        CASE WHEN efetivos.tipo in  (\'EG\')
                                                            THEN concat(LPAD(MONTH(efetivos.data_programada), 2, 0), \'-\', YEAR(efetivos.data_programada))
                                                            ELSE concat(LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada)), 2, 0), \'-\', YEAR(COALESCE(movimentacaos.data_pagamento, efetivos.data_programada))) 
                                                        END AS mes_referencia, 
                                                        CASE WHEN efetivos.tipo in  (\'EG\')
                                                            THEN count(efetivos.id)
                                                            ELSE count(COALESCE(movimentacaos.id, efetivos.id ))
                                                        END AS total,
                                                        SUM(CASE WHEN efetivos.tipo = (\'CP\') THEN 1 ELSE 0 END) as compra,
                                                        SUM(CASE WHEN efetivos.tipo = (\'VD\') THEN 1 ELSE 0 END) as venda,
                                                        SUM(CASE WHEN efetivos.tipo = (\'EG\') THEN 1 ELSE 0 END) as engorda'))
                                        ->orderBy(DB::raw('
                                                            CASE WHEN efetivos.tipo in (\'EG\')
                                                                THEN `efetivos`.`data_programada` 
                                                                ELSE COALESCE(movimentacaos.data_pagamento, efetivos.data_programada) 
                                                            END 
                                                        '), 'desc')
                                        ->get();
        } else {
            $efetivos = [];
        }

        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                        ->where('movimentacaos.segmento', 'MF')
                                        ->groupBy(DB::raw('concat(LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)), 2, 0), \'-\', YEAR(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)))'))
                                        ->select(DB::raw('concat(YEAR(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)), LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)), 2, 0)) AS mes_ordem,
                                                concat(LPAD(MONTH(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)), 2, 0), \'-\', YEAR(COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada))) AS mes_referencia,
                                                count( movimentacaos.id ) AS total,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN 1 ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN 1 ELSE 0 END) as despesa'))
                                        ->orderBy(DB::raw('COALESCE(movimentacaos.data_pagamento, movimentacaos.data_programada)'), 'desc')
                                        ->get();

        return view('painel.lancamento.index', compact('user', 'efetivos', 'movimentacaos', 'aba'));
    }

    public function refreshList(Request $request)
    {

        if(Gate::denies('view_lancamento')){
            return redirect()->route('logout');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if(!$request->has('tipo') || $request->tipo == null){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Processo para atualização não identificado.');

            return redirect()->route('painel');
        }

        $mensagem = [];
        $tipo = $request->tipo;

        switch($tipo) {
            case 'EP': {
                $empresas = Empresa::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

                foreach($empresas as $empresa)
                {
                    $list['id'] = $empresa->id;
                    $list['nome'] = $empresa->nome_empresa;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'FP': {
                $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                                                    ->where('status', 'A')
                                                    ->orderBy('produtor_id', 'desc')
                                                    ->orderBy('tipo_conta', 'asc')
                                                    ->get();

                foreach($forma_pagamentos as $forma_pagamento)
                {
                    $list['id'] = $forma_pagamento->id;
                    $list['nome'] = $forma_pagamento->forma;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'PT': {
                $produtors = Produtor::where('cliente_id', $user->cliente_user->cliente->id)
                                        ->where('status', 'A')
                                        ->orderBy('nome', 'asc')
                                        ->get();

                foreach($produtors as $produtor)
                {
                    $list['id'] = $produtor->id;
                    $list['nome'] = $produtor->nome_produtor;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'CR': {
                $categorias = Categoria::where('tipo', 'R')
                                        ->where('segmento', 'MF')
                                        ->where('status', 'A')
                                        ->orderBy('nome', 'asc')
                                        ->get();

                foreach($categorias as $categoria)
                {
                    $list['id'] = $categoria->id;
                    $list['nome'] = $categoria->nome;
                    array_push($mensagem, $list);
                }

                break;
            }            

            case 'CD': {
                $categorias = Categoria::where('tipo', 'D')
                                        ->where('segmento', 'MF')
                                        ->where('status', 'A')
                                        ->orderBy('nome', 'asc')
                                        ->get();

                foreach($categorias as $categoria)
                {
                    $list['id'] = $categoria->id;
                    $list['nome'] = $categoria->nome;
                    array_push($mensagem, $list);
                }

                break;
            }                                    

            case 'OR': {
                $fazendas = Fazenda::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

                foreach($fazendas as $fazenda)
                {
                    $list['id'] = $fazenda->id;
                    $list['nome'] = $fazenda->nome_fazenda;
                    array_push($mensagem, $list);
                }

                break;
            }

            case 'DT': {
                $fazendas = Fazenda::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

                foreach($fazendas as $fazenda)
                {
                    $list['id'] = $fazenda->id;
                    $list['nome'] = $fazenda->nome_fazenda;
                    array_push($mensagem, $list);
                }

                break;
            }            
        }

        return response()->json(['mensagem' => $mensagem]);
    }
}
