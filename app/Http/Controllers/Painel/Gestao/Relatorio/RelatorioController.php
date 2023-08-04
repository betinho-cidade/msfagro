<?php

namespace App\Http\Controllers\Painel\Gestao\Relatorio;

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
use Carbon\Carbon;
use Excel;
use App\Exports\MovimentacaosGestaoExport;


class RelatorioController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(Gate::denies('view_relatorio_gestao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $clientes = Cliente::where('status', 'A')
                             ->orderBy('nome', 'asc')
                             ->get();


        $search = [];
        $movimentacaos = [];

        return view('painel.gestao.relatorio.index', compact('user', 'clientes', 'search', 'movimentacaos'));        
    }


    public function search(Request $request)
    {
        if(Gate::denies('view_relatorio_gestao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $data_inicio = ($request->has('data_inicio') ? $request->data_inicio : null);
        $data_fim = ($request->has('data_fim') ? $request->data_fim : null);

        if(($data_inicio && $data_fim) && $data_inicio > $data_fim){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A data de início não pode ser maior que a data final.');

            return redirect()->route('relatorio_gestao.index');
        }

        $cliente = '';
        if($request->cliente){
            $cliente = Cliente::where('id', $request->cliente)
                            ->first();
        }

        if($request->has('data_inicio') ||
            $request->has('data_fim') ||
            $request->has('item_texto') ||
            $request->has('tipo_movimentacao') ||
            $request->has('segmento') ||
            $request->has('cliente')
        ){
            $search = [
                'data_inicio' => ($request->data_inicio) ? $request->data_inicio : '',
                'data_fim' => ($request->data_fim) ? $request->data_fim : '',
                'item_texto' => ($request->item_texto) ? $request->item_texto : '',
                'tipo_movimentacao' => ($request->tipo_movimentacao) ? $request->tipo_movimentacao : '',
                'segmento' => ($request->segmento) ? $request->segmento : '',
                'cliente' => ($request->cliente) ? $request->cliente : '',
            ];
        } else{
            $search = [];
        }

        $movimentacaos = Movimentacao::where(function($query) use ($search){
                                            if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            if($search['tipo_movimentacao']){
                                                $query->where('tipo', $search['tipo_movimentacao']);
                                            }

                                            if($search['cliente']){
                                                $query->where('cliente_id', $search['cliente']);
                                            }

                                            if($search['item_texto']){
                                                $query->where('item_texto', 'like', '%' . $search['item_texto'] . '%');
                                            }                                            

                                            if($search['data_inicio'] && $search['data_fim']){
                                                $query->where('data_programada', '>=', $search['data_inicio']);
                                                $query->where('data_programada', '<=', $search['data_fim']);
                                            } elseif($search['data_inicio']){
                                                $query->where('data_programada', '>=', $search['data_inicio']);
                                            } elseif($search['data_fim']){
                                                $query->where('data_programada', '<=', $search['data_fim']);
                                            }
                                        })
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $clientes = Cliente::where('status', 'A')
                            ->orderBy('nome', 'asc')
                            ->get();

        
        return view('painel.gestao.relatorio.index', compact('user', 'movimentacaos', 'clientes', 'search'));
    }


    public function excell(Request $request)
    {
        if(Gate::denies('view_relatorio_gestao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$request->search){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Necessário realizar uma busca inicialmente.');

            return redirect()->route('relatorio_gestao.index');
        }        


        return Excel::download(new MovimentacaosGestaoExport($request->search), 'movimentos.xlsx');
    }    


    public function pdf(Request $request)
    {
        if(Gate::denies('view_relatorio_gestao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        // if(!$user->cliente){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

        //     return redirect()->route('painel');
        // }

        if(!$request->search){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Necessário realizar uma busca inicialmente.');

            return redirect()->route('relatorio_gestao.index');
        }

        return Excel::download(new MovimentacaosGestaoExport($request->search), 'movimentos.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
    }        

}
