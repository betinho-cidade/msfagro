<?php

namespace App\Http\Controllers\Painel\Relatorio;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Efetivo;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use App\Models\Cliente;
use App\Models\ClienteGooglemap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//use App\Http\Requests\Lancamento\Efetivo\CreateRequest;
//use App\Http\Requests\Lancamento\Efetivo\UpdateRequest;
use Carbon\Carbon;
use Excel;
use App\Exports\MovimentacaosExport;
use App\Exports\MovimentacaosPdfExport;

use PDF;

class RelatorioController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $empresas = Empresa::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

        $produtors = Produtor::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        $search = [];
        $movimentacaos = [];

        return view('painel.relatorio.index', compact('user', 'empresas', 'produtors', 'forma_pagamentos', 'search', 'movimentacaos'));        
    }

    public function search(Request $request)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $data_inicio = ($request->has('data_inicio') ? $request->data_inicio : null);
        $data_fim = ($request->has('data_fim') ? $request->data_fim : null);

        if(($data_inicio && $data_fim) && $data_inicio > $data_fim){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A data de início não pode ser maior que a data final.');

            return redirect()->route('relatorio.index');
        }

        $empresa = '';
        if($request->empresa){
            $empresa = Empresa::where('cliente_id', $user->cliente_user->cliente->id)
                            ->where('id', $request->empresa)
                            ->first();
        }

        $produtor = '';
        if($request->produtor){
            $produtor = Produtor::where('cliente_id', $user->cliente_user->cliente->id)
                            ->where('id', $request->produtor)
                            ->first();
        }

        $forma_pagamento = '';
        if($request->forma_pagamento){
            $forma_pagamento = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                            ->where('id', $request->forma_pagamento)
                            ->first();
        }

        if($request->has('data_inicio') ||
            $request->has('data_fim') ||
            $request->has('item_texto') ||
            $request->has('nota') ||
            $request->has('movimentacao') ||
            $request->has('tipo_movimentacao') ||
            $request->has('produtor') ||
            $request->has('forma_pagamento') ||
            $request->has('segmento') ||
            $request->has('empresa')
        ){
            $search = [
                'tipo_cliente' => $user->cliente_user->cliente->tipo,
                'data_inicio' => ($request->data_inicio) ? $request->data_inicio : '',
                'data_fim' => ($request->data_fim) ? $request->data_fim : '',
                'item_texto' => ($request->item_texto) ? $request->item_texto : '',
                'nota' => ($request->nota) ? $request->nota : '',
                'movimentacao' => ($request->movimentacao) ? $request->movimentacao : '',
                'tipo_movimentacao' => ($request->tipo_movimentacao) ? $request->tipo_movimentacao : '',
                'produtor' => ($request->produtor) ? $request->produtor : '',
                'forma_pagamento' => ($request->forma_pagamento) ? $request->forma_pagamento : '',
                'segmento' => ($request->segmento) ? $request->segmento : '',
                'empresa' => ($request->empresa) ? $request->empresa : '',
            ];
        } else {
            if($request->has('search')){

                $params = json_decode($request->search);

                $search = [
                    'tipo_cliente' => $user->cliente_user->cliente->tipo,
                    'data_inicio' => ($params->data_inicio) ? $params->data_inicio : '',
                    'data_fim' => ($params->data_fim) ? $params->data_fim : '',
                    'item_texto' => ($params->item_texto) ? $params->item_texto : '',
                    'nota' => ($params->nota) ? $params->nota : '',
                    'movimentacao' => ($params->movimentacao) ? $params->movimentacao : '',
                    'tipo_movimentacao' => ($params->tipo_movimentacao) ? $params->tipo_movimentacao : '',
                    'produtor' => ($params->produtor) ? $params->produtor : '',
                    'forma_pagamento' => ($params->forma_pagamento) ? $params->forma_pagamento : '',
                    'segmento' => ($params->segmento) ? $params->segmento : '',
                    'empresa' => ($params->empresa) ? $params->empresa : '',
                ];
            }else{
                $search = [];
            }
        }

        $dados = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                        ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            if($search['tipo_movimentacao']){
                                                $query->where('tipo', $search['tipo_movimentacao']);
                                            }

                                            if($search['produtor']){
                                                $query->where('produtor_id', $search['produtor']);
                                            }

                                            if($search['empresa']){
                                                $query->where('empresa_id', $search['empresa']);
                                            }

                                            if($search['forma_pagamento']){
                                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                                            }

                                            if($search['item_texto']){
                                                $query->where('item_texto', 'like', '%' . $search['item_texto'] . '%');
                                            }          
                                            
                                            if($search['nota']){
                                                $query->where('nota', 'like', '%' . $search['nota'] . '%');
                                            }                                                      

                                            if($search['data_inicio'] && $search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else {
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }        
                                            } elseif($search['data_inicio']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                }   
                                            } elseif($search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }     
                                            } else {
                                                if($search['movimentacao'] == 'F'){
                                                    $query->whereNull('data_pagamento');
                                                }else if($search['movimentacao'] == 'E'){
                                                    $query->whereNotNull('data_pagamento');
                                                }                                                
                                            }
                                        });
                                        //->whereYear('data_programada', $data_programada_vetor[1])
                                        //->get();

        if($search['data_inicio'] && $search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_programada desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_pagamento desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }        
        } elseif($search['data_inicio']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_programada desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_pagamento desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }
            } else{
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }   
        } elseif($search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_programada desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by data_pagamento desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                        ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                        ->get();
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }     
        } else {
            if($search['movimentacao'] == 'F'){
                $movimentacaos = $dados->orderBy('data_programada', 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by data_programada desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }else if($search['movimentacao'] == 'E'){
                $movimentacaos = $dados->orderBy('data_pagamento', 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by data_pagamento desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }else if($search['movimentacao'] == 'G'){
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')
                    ->select(DB::raw('ROW_NUMBER() over (order by COALESCE(data_pagamento, data_programada) desc) as ordenacao, movimentacaos.*'))
                    ->get();
            }                                                
        }

                                       
        $empresas = Empresa::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

        $produtors = Produtor::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente_user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        return view('painel.relatorio.index', compact('user', 'movimentacaos', 'empresas', 'produtors', 'forma_pagamentos', 'search'));
    }

    public function excell(Request $request)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if(!$request->search){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Necessário realizar uma busca inicialmente.');

            return redirect()->route('relatorio.index');
        }        


        return Excel::download(new MovimentacaosExport($request->search), 'movimentos.xlsx');
    }    

    public function pdf(Request $request)
    {
        if(Gate::denies('view_relatorio')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if(!$request->search){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Necessário realizar uma busca inicialmente.');

            return redirect()->route('relatorio.index');
        }

        $search = $request->search;

        $dados = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                      ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }    

                                            if($search['tipo_movimentacao']){
                                                $query->where('tipo', $search['tipo_movimentacao']);
                                            }

                                            if($search['produtor']){
                                                $query->where('produtor_id', $search['produtor']);
                                            }

                                            if($search['empresa']){
                                                $query->where('empresa_id', $search['empresa']);
                                            }

                                            if($search['forma_pagamento']){
                                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                                            }                                            

                                            if($search['item_texto']){
                                                $query->where('item_texto', 'like', '%' . $search['item_texto'] . '%');
                                            }       
                                            
                                            if($search['nota']){
                                                $query->where('nota', 'like', '%' . $search['nota'] . '%');
                                            }        
                                            
                                            if($search['data_inicio'] && $search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else {
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }        
                                            } elseif($search['data_inicio']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                }   
                                            } elseif($search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                }     
                                            } else {
                                                if($search['movimentacao'] == 'F'){
                                                    $query->whereNull('data_pagamento');
                                                }else if($search['movimentacao'] == 'E'){
                                                    $query->whereNotNull('data_pagamento');
                                                }                                                
                                            }                                            
                                        })
                                        ->orderBy('tipo', 'desc'); // primeiro por Despesa, depois por Receita
                                        
        if($search['data_inicio'] && $search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
            }        
        } elseif($search['data_inicio']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
                }
            } else{
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
            }   
        } elseif($search['data_fim']){
            if($search['movimentacao']){
                if($search['movimentacao'] == 'F'){
                    $movimentacaos = $dados->orderBy('data_programada', 'desc')->get();
                }else if($search['movimentacao'] == 'E'){
                    $movimentacaos = $dados->orderBy('data_pagamento', 'desc')->get();
                }else if($search['movimentacao'] == 'G'){
                    $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
                }
            } else {
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
            }     
        } else {
            if($search['movimentacao'] == 'F'){
                $movimentacaos = $dados->orderBy('data_programada', 'desc')->get();
            }else if($search['movimentacao'] == 'E'){
                $movimentacaos = $dados->orderBy('data_pagamento', 'desc')->get();
            }else if($search['movimentacao'] == 'G'){
                $movimentacaos = $dados->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc')->get();
            }                                                
        }


        $receita = $movimentacaos->where('tipo', '=', 'R')->sum('valor');
        $despesa = $movimentacaos->where('tipo', '=', 'D')->sum('valor');
        $total = $receita - $despesa;
        $saldo = ($total >= 0) ? 'P' : 'N';

        $resultado_final = [
            'receita' => $receita,
            'despesa' => $despesa,
            'total' => $total,
            'saldo' => $saldo,
        ];

        $download = '';
        $dompdf = PDF::loadView('painel.relatorio.relatorio', compact('movimentacaos','resultado_final','user'));
        $dompdf->setPaper('a4', 'landscape');
        
        return $dompdf->download('movimentacoes.pdf');

        //return Excel::download(new MovimentacaosPdfExport($request->search), 'movimentos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }         

    public function geomaps(Request $request)
    {
        if(Gate::denies('view_relatorio_maps')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        // if($user->cliente_user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Visualização permitida somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // } 
        
        $anomes_referencia = Carbon::now();
        $cliente_googlemap = ClienteGooglemap::where('cliente_id', $user->cliente_user->cliente->id)
                                             ->whereYear('anomes_referencia', $anomes_referencia->year)
                                             ->whereMonth('anomes_referencia', $anomes_referencia->month)
                                             ->first();       

        $cliente = Cliente::where('id', $user->cliente_user->cliente->id)                                     
                           ->first();        
                
        if( (!$cliente_googlemap && $cliente->qtd_apimaps == 0) ||
            ($cliente_googlemap && ($cliente->qtd_apimaps <= $cliente_googlemap->qtd_apimaps))){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O limite mensal ('.$cliente->qtd_apimaps.') de visualizações do Mapa foi atingido. Favor aguardar o próximo mês.');

            return redirect()->route('painel');
        }               

        $fazendas = Fazenda::where('cliente_id', $user->cliente_user->cliente->id)
                                    ->where('status', 'A')
                                    ->where('latitude', '<>', '0')
                                    ->where('longitude', '<>', '0')
                                    ->orderBy('nome', 'asc')
                                    ->get();
        $message = '';

        try {

            DB::beginTransaction();
    
            if($cliente_googlemap){
                $cliente_googlemap->qtd_apimaps = $cliente_googlemap->qtd_apimaps + 1;
                    
                $cliente_googlemap->save();
            } else {
                $new_cliente_googlemap = new ClienteGooglemap();

                $new_cliente_googlemap->cliente_id = $user->cliente_user->cliente->id;
                $new_cliente_googlemap->anomes_referencia = $anomes_referencia;
                $new_cliente_googlemap->qtd_apimaps = 1;
                $new_cliente_googlemap->qtd_geolocation = 0;

                $new_cliente_googlemap->save();                    
            }
    
            DB::commit();
    
        } catch (Exception $ex){
    
            DB::rollBack();
    
            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();
        }                                    

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
            
            return redirect()->route('painel');
        }

        return view('painel.relatorio.geomaps', compact('user', 'fazendas'));        
    }

    // REPLICADA na controller EfetivoController (destroy) - App\Http\Controllers\Painel\Lancamento\Efetivo
    public function destroy_efetivo(Efetivo $efetivo, Request $request)
    {
        if(Gate::denies('delete_efetivo')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $search = $request->search_efetivo;

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->tipo == 'AG'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Lançamentos permitidos somente para o perfil Pecuarista.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->id != $efetivo->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Efetivo Pecuário não pertence ao cliente informado.');

            return redirect()->route('relatorio.search', ['search' => $search]);
        }

        $message = '';
        $efetivo_id = $efetivo->id;
        $efetivo_tipo_texto = $efetivo->tipo_efetivo_texto;

        $ano_mes = Carbon::createFromFormat('Y-m-d', $efetivo->data_programada_ajustada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {
            DB::beginTransaction();

            $retorno_estoque = $this->atualizaEstoqueEfetivo($efetivo, true);

            if($retorno_estoque && $retorno_estoque != 'SALDO_OK') {
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', $retorno_estoque);

                DB::rollBack();

                return redirect()->route('relatorio.search', ['search' => $search]);
            }

            $efetivo_arquivos = [];
            $efetivo_arquivos[0]['cliente_id'] = $efetivo->cliente_id;
            $efetivo_arquivos[0]['efetivo_id'] = $efetivo->id;
            $efetivo_arquivos[0]['movimentacao_id'] = ($efetivo->movimentacao) ? $efetivo->movimentacao->id : 0;
            $efetivo_arquivos[0]['path_gta'] = $efetivo->path_gta;

            if($efetivo->movimentacao){

                if($efetivo->movimentacao->tipo == 'D') {
                    $efetivo->movimentacao->delete_notification();
                }            

                $efetivo_arquivos[0]['path_nota'] = $efetivo->movimentacao->path_nota;
                $efetivo_arquivos[0]['path_comprovante'] = $efetivo->movimentacao->path_comprovante;
                $efetivo_arquivos[0]['path_anexo'] = $efetivo->movimentacao->path_anexo;
                $efetivo->movimentacao->delete();
            }

            $efetivo->delete();

            $this->destroy_files_efetivo($efetivo_arquivos);

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'Integrity constraint violation') !== false){
                $message = "Não foi possível excluir o registro, pois existem referências ao mesmo em outros processos.";
            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O Efetivo Pecuário ('.$efetivo_tipo_texto.') com ID <span style="color: #af1e1e;">'. $efetivo_id .'</span> foi excluído com sucesso');
        }

        return redirect()->route('relatorio.search', ['search' => $search]);
    }    

    // REPLICADA na controller EfetivoController (atualizaEstoque) - App\Http\Controllers\Painel\Lancamento\Efetivo
    protected function atualizaEstoqueEfetivo(Efetivo $efetivo, bool $desfazerefetivo){

        $message = 'SALDO_OK';

        if($desfazerefetivo){ //Desfaz o Lançamento de quantidades de bovinos originalmente realizado.
            switch ($efetivo->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $efetivo->qtd_femea;

                    if($destino->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($destino->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $efetivo->qtd_femea;
                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho + $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea + $efetivo->qtd_femea;
                    $origem->save();

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho - $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea - $efetivo->qtd_femea;

                    if($destino->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($destino->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($destino->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $destino->save();
                    break;
                }
            }

        } else{
            switch ($efetivo->tipo){
                case 'CP' : {

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $efetivo->qtd_femea;
                    $destino->save();
                    break;
                }

                case 'VD' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $efetivo->qtd_femea;

                    if($origem->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($origem->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $origem->save();
                    break;
                }

                case 'EG' : {

                    $origem = Fazenda::where('id', $efetivo->origem_id)->first();
                    $origem->qtd_macho = $origem->qtd_macho - $efetivo->qtd_macho;
                    $origem->qtd_femea = $origem->qtd_femea - $efetivo->qtd_femea;

                    if($origem->qtd_macho < 0){
                        $message = 'A quantidade de MACHOS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_macho + $efetivo->qtd_macho).') - movimentação ('.$efetivo->qtd_macho.')';
                        return $message;
                    }

                    if($origem->qtd_femea < 0){
                        $message = 'A quantidade de FÊMEAS na Fazenda é inferior a que está sendo movimentada. Fazenda ('.($origem->qtd_femea + $efetivo->qtd_femea).') - movimentação ('.$efetivo->qtd_femea.')';
                        return $message;
                    }

                    $origem->save();

                    $destino = Fazenda::where('id', $efetivo->destino_id)->first();
                    $destino->qtd_macho = $destino->qtd_macho + $efetivo->qtd_macho;
                    $destino->qtd_femea = $destino->qtd_femea + $efetivo->qtd_femea;
                    $destino->save();
                    break;
                }
            }
        }

        return $message;
    }

    // REPLICADA na controller EfetivoController (destroy_files) - App\Http\Controllers\Painel\Lancamento\Efetivo
    protected function destroy_files_efetivo(Array $efetivo_arquivos){

        foreach($efetivo_arquivos as $efetivo){

            $path_gta = 'documentos/'. $efetivo['cliente_id'] . '/gtas/';
            if($efetivo['path_gta']){
                if(Storage::exists($path_gta)) {
                    Storage::delete($path_gta . $efetivo['path_gta']);
                }
            }

            $path_nota = 'documentos/'. $efetivo['cliente_id'] . '/notas/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_nota']){
                if(Storage::exists($path_nota)) {
                    Storage::delete($path_nota . $efetivo['path_nota']);
                }
            }

            $path_comprovante = 'documentos/'. $efetivo['cliente_id'] . '/comprovantes/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_comprovante']){
                if(Storage::exists($path_comprovante)) {
                    Storage::delete($path_comprovante . $efetivo['path_comprovante']);
                }
            }

            $path_anexo = 'documentos/'. $efetivo['cliente_id'] . '/anexos/';
            if($efetivo['movimentacao_id'] != 0 && $efetivo['path_anexo']){
                if(Storage::exists($path_anexo)) {
                    Storage::delete($path_anexo . $efetivo['path_anexo']);
                }
            }            

        }
    }

    // REPLICADA na controller MovimentacaoController (destroy) - App\Http\Controllers\Painel\Lancamento\Movimentacao
    public function destroy_movimentacao(Movimentacao $movimentacao, Request $request)
    {
        if(Gate::denies('delete_movimentacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $search = $request->search_movimentacao;
        
        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente_user->cliente->id != $movimentacao->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Movimentação Fiscal não pertence ao cliente informado.');

            return redirect()->route('relatorio.search', ['search' => $search]);
        }

        $message = '';
        $movimentacao_id = $movimentacao->id;
        $movimentacao_tipo_texto = $movimentacao->tipo_movimentacao_texto;

        $ano_mes = Carbon::createFromFormat('Y-m-d', $movimentacao->data_programada_ajustada);
        $mes_referencia = Str::padLeft($ano_mes->month, 2, '0') . '-' . $ano_mes->year;

        try {
            DB::beginTransaction();

            $movimentacao_arquivos = [];
            $movimentacao_arquivos[0]['cliente_id'] = $movimentacao->cliente_id;
            $movimentacao_arquivos[0]['movimentacao_id'] = $movimentacao->id;
            $movimentacao_arquivos[0]['path_nota'] = $movimentacao->path_nota;
            $movimentacao_arquivos[0]['path_comprovante'] = $movimentacao->path_comprovante;
            $movimentacao_arquivos[0]['path_anexo'] = $movimentacao->path_anexo;

            if($movimentacao->tipo == 'D') {
                $movimentacao->delete_notification();
            }            
            
            $movimentacao->delete();
            
            $this->destroy_files_movimentacao($movimentacao_arquivos);

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'Integrity constraint violation') !== false){
                $message = "Não foi possível excluir o registro, pois existem referências ao mesmo em outros processos.";
            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }

        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Movimentação Fiscal ('.$movimentacao_tipo_texto.') com ID <span style="color: #af1e1e;">'. $movimentacao_id .'</span> foi excluída com sucesso');
        }

        return redirect()->route('relatorio.search', ['search' => $search]);
    }

    // REPLICADA na controller MovimentacaoController (destroy_files) - App\Http\Controllers\Painel\Lancamento\Movimentacao
    protected function destroy_files_movimentacao(Array $movimentacao_arquivos){

        foreach($movimentacao_arquivos as $movimentacao){

            $path_nota = 'documentos/'. $movimentacao['cliente_id'] . '/notas/';
            if($movimentacao['movimentacao_id'] != 0 && $movimentacao['path_nota']){
                if(Storage::exists($path_nota)) {
                    Storage::delete($path_nota . $movimentacao['path_nota']);
                }
            }

            $path_comprovante = 'documentos/'. $movimentacao['cliente_id'] . '/comprovantes/';
            if($movimentacao['movimentacao_id'] != 0 && $movimentacao['path_comprovante']){
                if(Storage::exists($path_comprovante)) {
                    Storage::delete($path_comprovante . $movimentacao['path_comprovante']);
                }
            }

            $path_anexo = 'documentos/'. $movimentacao['cliente_id'] . '/anexos/';
            if($movimentacao['movimentacao_id'] != 0 && $movimentacao['path_anexo']){
                if(Storage::exists($path_anexo)) {
                    Storage::delete($path_anexo . $movimentacao['path_anexo']);
                }
            }            
        }
    }    

}
