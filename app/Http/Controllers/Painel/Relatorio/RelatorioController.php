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
        } else{
            $search = [];
        }

        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                        ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            // if($search['movimentacao']){
                                            //     if($search['movimentacao'] == 'F'){
                                            //         $query->whereNull('data_pagamento');
                                            //     }else if($search['movimentacao'] == 'E'){
                                            //         $query->whereNotNull('data_pagamento');
                                            //     }
                                            // }                                           

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
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else {
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }        
                                            } elseif($search['data_inicio']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }   
                                            } elseif($search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }     
                                            } else {
                                                if($search['movimentacao'] == 'F'){
                                                    $query->whereNull('data_pagamento');
                                                    $query->orderBy('data_programada', 'desc');
                                                }else if($search['movimentacao'] == 'E'){
                                                    $query->whereNotNull('data_pagamento');
                                                    $query->orderBy('data_pagamento', 'desc');
                                                }else if($search['movimentacao'] == 'G'){
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }                                                
                                            }
                                        })
                                        //->whereYear('data_programada', $data_programada_vetor[1])
                                        //->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

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


    // public function pdf(Request $request)
    // {
    //     if(Gate::denies('view_relatorio')){
    //         abort('403', 'Página não disponível');
    //         //return redirect()->back();
    //     }

    //     $user = Auth()->User();

    //     if(!$user->cliente_user){
    //         $request->session()->flash('message.level', 'warning');
    //         $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

    //         return redirect()->route('painel');
    //     }

    //     if(!$request->search){
    //         $request->session()->flash('message.level', 'warning');
    //         $request->session()->flash('message.content', 'Necessário realizar uma busca inicialmente.');

    //         return redirect()->route('relatorio.index');
    //     }

    //     return Excel::download(new MovimentacaosPdfExport($request->search), 'movimentos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    // }        


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

        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente_user->cliente->id)
                                      ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }    

                                            // if($search['movimentacao']){
                                            //     if($search['movimentacao'] == 'F'){
                                            //         $query->whereNull('data_pagamento');
                                            //     }else if($search['movimentacao'] == 'E'){
                                            //         $query->whereNotNull('data_pagamento');
                                            //     }
                                            // }                                            

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
                                            
                                            // if($search['data_inicio'] && $search['data_fim']){
                                            //     if($search['movimentacao']){
                                            //         if($search['movimentacao'] == 'F'){
                                            //             $query->where('data_programada', '>=', $search['data_inicio']);
                                            //             $query->where('data_programada', '<=', $search['data_fim']);
                                            //             $query->orderBy('data_programada', 'desc');
                                            //         }else if($search['movimentacao'] == 'E'){
                                            //             $query->where('data_pagamento', '>=', $search['data_inicio']);
                                            //             $query->where('data_pagamento', '<=', $search['data_fim']);
                                            //             $query->orderBy('data_pagamento', 'desc');
                                            //         }
                                            //     } else {
                                            //         $query->where('data_programada', '>=', $search['data_inicio']);
                                            //         $query->where('data_programada', '<=', $search['data_fim']);
                                            //         $query->orderBy('data_programada', 'desc');
                                            //     }        
                                            // } elseif($search['data_inicio']){
                                            //     if($search['movimentacao']){
                                            //         if($search['movimentacao'] == 'F'){
                                            //             $query->where('data_programada', '>=', $search['data_inicio']);
                                            //             $query->orderBy('data_programada', 'desc');
                                            //         }else if($search['movimentacao'] == 'E'){
                                            //             $query->where('data_pagamento', '>=', $search['data_inicio']);
                                            //             $query->orderBy('data_pagamento', 'desc');
                                            //         }
                                            //     } else{
                                            //         $query->where('data_programada', '>=', $search['data_inicio']);
                                            //         $query->orderBy('data_programada', 'desc');
                                            //     }   
                                            // } elseif($search['data_fim']){
                                            //     if($search['movimentacao']){
                                            //         if($search['movimentacao'] == 'F'){
                                            //             $query->where('data_programada', '<=', $search['data_fim']);
                                            //             $query->orderBy('data_programada', 'desc');
                                            //         }else if($search['movimentacao'] == 'E'){
                                            //             $query->where('data_pagamento', '<=', $search['data_fim']);
                                            //             $query->orderBy('data_pagamento', 'desc');
                                            //         }
                                            //     } else{
                                            //         $query->where('data_programada', '>=', $search['data_inicio']);
                                            //         $query->orderBy('data_programada', 'desc');
                                            //     }     
                                            // } else {
                                            //     $query->orderBy('data_programada', 'desc');
                                            // }

                                            if($search['data_inicio'] && $search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else {
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }        
                                            } elseif($search['data_inicio']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '>=', $search['data_inicio']);
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '>=', $search['data_inicio']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) >= "'.$search['data_inicio'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }   
                                            } elseif($search['data_fim']){
                                                if($search['movimentacao']){
                                                    if($search['movimentacao'] == 'F'){
                                                        $query->whereNull('data_pagamento');
                                                        $query->where('data_programada', '<=', $search['data_fim']);
                                                        $query->orderBy('data_programada', 'desc');
                                                    }else if($search['movimentacao'] == 'E'){
                                                        $query->whereNotNull('data_pagamento');
                                                        $query->where('data_pagamento', '<=', $search['data_fim']);
                                                        $query->orderBy('data_pagamento', 'desc');
                                                    }else if($search['movimentacao'] == 'G'){
                                                        $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                        $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                    }
                                                } else{
                                                    $query->whereRaw(DB::raw('COALESCE(data_pagamento, data_programada) <= "'.$search['data_fim'].'"'));
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }     
                                            } else {
                                                if($search['movimentacao'] == 'F'){
                                                    $query->whereNull('data_pagamento');
                                                    $query->orderBy('data_programada', 'desc');
                                                }else if($search['movimentacao'] == 'E'){
                                                    $query->whereNotNull('data_pagamento');
                                                    $query->orderBy('data_pagamento', 'desc');
                                                }else if($search['movimentacao'] == 'G'){
                                                    $query->orderBy(DB::raw('COALESCE(data_pagamento, data_programada)'), 'desc');
                                                }                                                
                                            }                                            
                                        })
                                        ->orderBy('tipo', 'desc') // primeiro por Despesa, depois por Receita
                                        //->orderBy('movimentacaos.data_programada', 'asc')
                                        ->get();            

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

}
