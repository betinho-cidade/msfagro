<?php

namespace App\Http\Controllers\Painel\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Produtor;
use App\Models\Categoria;
use App\Models\Movimentacao;
use App\Models\FormaPagamento;
use App\Models\Fazenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Lancamento\Movimentacao\CreateRequest;
use App\Http\Requests\Lancamento\Movimentacao\UpdateRequest;
use Carbon\Carbon;



class FinanceiroController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(Gate::denies('view_financeiro')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $movimentacao_global = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->groupBy(DB::raw('concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada))'))
                                        ->select(DB::raw('concat(YEAR(movimentacaos.data_programada), LPAD(MONTH(movimentacaos.data_programada), 2, 0)) AS mes_ordem,
                                                concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada)) AS mes_referencia,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) - SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) AS saldo,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa'))
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $movimentacao_efetiva = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where('situacao', 'PG')
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->groupBy(DB::raw('concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada))'))
                                        ->select(DB::raw('concat(YEAR(movimentacaos.data_programada), LPAD(MONTH(movimentacaos.data_programada), 2, 0)) AS mes_ordem,
                                                concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada)) AS mes_referencia,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) - SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) AS saldo,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa'))
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $movimentacao_futura = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where('situacao', 'PD')
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->groupBy(DB::raw('concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada))'))
                                        ->select(DB::raw('concat(YEAR(movimentacaos.data_programada), LPAD(MONTH(movimentacaos.data_programada), 2, 0)) AS mes_ordem,
                                                concat(LPAD(MONTH(movimentacaos.data_programada), 2, 0), \'-\', YEAR(movimentacaos.data_programada)) AS mes_referencia,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) - SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) AS saldo,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa'))
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $saldo_global = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->select(DB::raw('SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) - SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) AS saldo'))
                                        ->first();

        $saldo_efetivo = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where('situacao', 'PG')
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->select(DB::raw('SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa'))
                                        ->first();

        $saldo_futuro = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where('situacao', 'PD')
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->select(DB::raw('SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa'))
                                        ->first();

        return view('painel.financeiro.index', compact('user', 'movimentacao_global', 'movimentacao_efetiva', 'movimentacao_futura', 'saldo_global', 'saldo_efetivo', 'saldo_futuro'));
    }

    public function list(Request $request)
    {
        if(Gate::denies('list_financeiro')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $status_movimentacao = ($request->has('status_movimentacao') ? $request->status_movimentacao : null);

        if(!$status_movimentacao || ($status_movimentacao != 'GB' && $status_movimentacao != 'PD' && $status_movimentacao != 'PG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível definir o status de movimentação solicitado (Global, Efetiva ou Futura).');

            return redirect()->route('financeiro.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('financeiro.index');
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        $texto_movimentacao = '';
        switch($status_movimentacao) {
            case 'GB' : {
                $texto_movimentacao = 'Movimentação Global';
                break;
            }
            case 'PD' : {
                $texto_movimentacao = 'Movimentação Futura';
                break;
            }
            case 'PG' : {
                $texto_movimentacao = 'Movimentação Efetiva';
                break;
            }
        }

        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where(function($query) use ($status_movimentacao){
                                            if($status_movimentacao != 'GB'){
                                                $query->where('situacao', $status_movimentacao);
                                            }
                                        })
                                        ->where(function($query) use ($user){
                                            if($user->cliente->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->whereYear('data_programada', $data_programada_vetor[1])
                                        ->whereMonth('data_programada', $data_programada_vetor[0])
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $empresas = Empresa::where('cliente_id', $user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        $search = [];

        return view('painel.financeiro.list', compact('user', 'movimentacaos', 'status_movimentacao', 'texto_movimentacao', 'data_programada', 'mes_referencia', 'empresas', 'produtors', 'forma_pagamentos', 'search'));
    }


    public function search(Request $request)
    {
        if(Gate::denies('list_financeiro')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $status_movimentacao = ($request->has('status_movimentacao') ? $request->status_movimentacao : null);

        if(!$status_movimentacao || ($status_movimentacao != 'GB' && $status_movimentacao != 'PD' && $status_movimentacao != 'PG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível definir o status de movimentação solicitado (Global, Efetiva ou Futura).');

            return redirect()->route('financeiro.index');
        }

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente ao mês de referência informado.');

            return redirect()->route('financeiro.index');
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        $texto_movimentacao = '';
        switch($status_movimentacao) {
            case 'GB' : {
                $texto_movimentacao = 'Movimentação Global';
                break;
            }
            case 'PD' : {
                $texto_movimentacao = 'Movimentação Futura';
                break;
            }
            case 'PG' : {
                $texto_movimentacao = 'Movimentação Efetiva';
                break;
            }
        }

        $tipo_movimentacao = '';
        if($request->tipo_movimentacao){
            switch($request->tipo_movimentacao){
                case 'R' : {
                    $tipo_movimentacao = 'Receita';
                    break;
                }
                case 'D' : {
                    $tipo_movimentacao = 'Despesa';
                    break;
                }
                default : {
                    $tipo_movimentacao = '---';
                    break;
                }
            }
        }

        $segmento = '';
        if($request->segmento){
            switch($request->segmento){
                case 'MG' : {
                    $segmento = 'Movimentação Bovina';
                    break;
                }
                case 'MF' : {
                    $segmento = 'Movimentação Fiscal';
                    break;
                }
                default : {
                    $segmento = '---';
                    break;
                }
            }
        }

        $empresa = '';
        if($request->empresa){
            $empresa = Empresa::where('cliente_id', $user->cliente->id)
                            ->where('id', $request->empresa)
                            ->first();
        }

        $produtor = '';
        if($request->produtor){
            $produtor = Produtor::where('cliente_id', $user->cliente->id)
                            ->where('id', $request->produtor)
                            ->first();
        }

        $forma_pagamento = '';
        if($request->forma_pagamento){
            $forma_pagamento = FormaPagamento::where('cliente_id', $user->cliente->id)
                            ->where('id', $request->forma_pagamento)
                            ->first();
        }


        if($request->has('tipo_movimentacao') ||
            $request->has('produtor') ||
            $request->has('forma_pagamento') ||
            $request->has('segmento') ||
            $request->has('empresa')
        ){
            $search = [
                'tipo_cliente' => ['param_key' => $user->cliente->tipo, 'param_value' => $user->cliente->tipo_cliente],
                'tipo_movimentacao' => ['param_key' => ($request->tipo_movimentacao) ? $request->tipo_movimentacao : '', 'param_value' => ($request->tipo_movimentacao) ? $tipo_movimentacao : ''],
                'produtor' => ['param_key' => ($request->produtor) ? $request->produtor : '', 'param_value' => ($request->produtor) ? $produtor->nome_reduzido : ''],
                'forma_pagamento' => ['param_key' => ($request->forma_pagamento) ? $request->forma_pagamento : '', 'param_value' => ($request->forma_pagamento) ? $forma_pagamento->forma : ''],
                'segmento' => ['param_key' => ($request->segmento) ? $request->segmento : '', 'param_value' => ($request->segmento) ? $segmento : ''],
                'empresa' => ['param_key' => ($request->empresa) ? $request->empresa : '', 'param_value' => ($request->empresa) ? $empresa->nome_reduzido : ''],
            ];
        } else{
            $search = [];
        }

        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where(function($query) use ($status_movimentacao){
                                            if($status_movimentacao != 'GB'){
                                                $query->where('situacao', $status_movimentacao);
                                            }
                                        })
                                        ->where(function($query) use ($search){
                                            if($search['tipo_cliente']['param_key'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']['param_key']){
                                                $query->where('segmento', $search['segmento']['param_key']);
                                            }

                                            if($search['tipo_movimentacao']['param_key']){
                                                $query->where('tipo', $search['tipo_movimentacao']['param_key']);
                                            }

                                            if($search['produtor']['param_key']){
                                                $query->where('produtor_id', $search['produtor']['param_key']);
                                            }

                                            if($search['empresa']['param_key']){
                                                $query->where('empresa_id', $search['empresa']['param_key']);
                                            }

                                            if($search['forma_pagamento']['param_key']){
                                                $query->where('forma_pagamento_id', $search['forma_pagamento']['param_key']);
                                            }
                                        })
                                        ->whereYear('data_programada', $data_programada_vetor[1])
                                        ->whereMonth('data_programada', $data_programada_vetor[0])
                                        ->orderBy('movimentacaos.data_programada', 'desc')
                                        ->get();

        $empresas = Empresa::where('cliente_id', $user->cliente->id)
                                    ->where('status', 'A')
                                    ->orderBy('nome', 'asc')
                                    ->get();

        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id', 'desc')
                                            ->orderBy('tipo_conta', 'asc')
                                            ->get();

        return view('painel.financeiro.list', compact('user', 'movimentacaos', 'status_movimentacao', 'texto_movimentacao', 'data_programada', 'mes_referencia', 'empresas', 'produtors', 'forma_pagamentos', 'search'));
    }



}
