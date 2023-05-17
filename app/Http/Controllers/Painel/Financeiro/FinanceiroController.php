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

        $tipo_movimentacao = ($request->has('tipo_movimentacao') ? $request->tipo_movimentacao : null);

        if(!$tipo_movimentacao || ($tipo_movimentacao != 'GB' && $tipo_movimentacao != 'PD' && $tipo_movimentacao != 'PG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível definir o tipo de movimentação solicitado.');

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
        switch($tipo_movimentacao) {
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
                                        ->where(function($query) use ($tipo_movimentacao){
                                            if($tipo_movimentacao != 'GB'){
                                                $query->where('situacao', $tipo_movimentacao);
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

        return view('painel.financeiro.list', compact('user', 'movimentacaos', 'tipo_movimentacao', 'texto_movimentacao', 'data_programada', 'mes_referencia', 'empresas', 'produtors', 'forma_pagamentos', 'search'));
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

        $tipo_movimentacao = ($request->has('tipo_movimentacao') ? $request->tipo_movimentacao : null);

        if(!$tipo_movimentacao || ($tipo_movimentacao != 'GB' && $tipo_movimentacao != 'PD' && $tipo_movimentacao != 'PG')){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível definir o tipo de movimentação solicitado.');

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
        switch($tipo_movimentacao) {
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

        if($request->has('tipo') ||
            $request->has('produtor') ||
            $request->has('forma_pagamento') ||
            $request->has('segmento') ||
            $request->has('empresa')
        ){
            $search = [
                'tipo_cliente' => $user->cliente->tipo,
                'tipo' => ($request->tipo) ? $request->tipo : '',
                'produtor' => ($request->produtor) ? $request->produtor : '',
                'forma_pagamento' => ($request->forma_pagamento) ? $request->forma_pagamento : '',
                'segmento' => ($request->segmento) ? $request->segmento : '',
                'empresa' => ($request->empresa) ? $request->empresa : '',
            ];
        } else {
            if ($request->has('search')){
                $search_fields = $request->search;

                $search = [
                    'tipo_cliente' => $user->cliente->tipo,
                    'tipo' => (array_key_exists('tipo', $search_fields)) ? $search_fields['tipo'] : '',
                    'produtor' => (array_key_exists('produtor', $search_fields)) ? $search_fields['produtor'] : '',
                    'forma_pagamento' => (array_key_exists('forma_pagamento', $search_fields)) ? $search_fields['forma_pagamento'] : '',
                    'segmento' => (array_key_exists('segmento', $search_fields)) ? $search_fields['segmento'] : '',
                    'empresa' => (array_key_exists('empresa', $search_fields)) ? $search_fields['empresa'] : '',
                ];
            } else {
                $search = [];
            }
        }


        $movimentacaos = Movimentacao::where('movimentacaos.cliente_id', $user->cliente->id)
                                        ->where(function($query) use ($tipo_movimentacao){
                                            if($tipo_movimentacao != 'GB'){
                                                $query->where('situacao', $tipo_movimentacao);
                                            }
                                        })
                                        ->where(function($query) use ($search){
                                            if($search['tipo_cliente'] == 'AG'){
                                                $query->where('segmento', 'MF');
                                            } else if($search['segmento']){
                                                $query->where('segmento', $search['segmento']);
                                            }

                                            if($search['tipo']){
                                                $query->where('tipo', $search['tipo']);
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

        return view('painel.financeiro.list', compact('user', 'movimentacaos', 'tipo_movimentacao', 'texto_movimentacao', 'data_programada', 'mes_referencia', 'empresas', 'produtors', 'forma_pagamentos', 'search'));
    }



}
