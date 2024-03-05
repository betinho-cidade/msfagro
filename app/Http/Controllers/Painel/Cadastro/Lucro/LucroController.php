<?php

namespace App\Http\Controllers\Painel\Cadastro\Lucro;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produtor;
use App\Models\Lucro;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Cadastro\Lucro\CreateRequest;
use App\Http\Requests\Cadastro\Lucro\UpdateRequest;
use Carbon\Carbon;



class LucroController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        if(Gate::denies('view_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $lucros = Lucro::where('cliente_id', $user->cliente->id)
                        ->orderBy('lucros.data_lancamento', 'desc')
                        ->get();


        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();      

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id')
                                            ->get();   
            
        $search = []; 

        $graphs = Lucro::where('lucros.cliente_id', $user->cliente->id)
                        ->join('produtors', 'lucros.produtor_id', '=', 'produtors.id')
                        ->where('produtors.cliente_id', $user->cliente->id)
                        ->selectRaw('produtors.nome as nome, sum(lucros.valor) as valor')
                        ->groupBy('produtors.nome')
                        ->get();                                        
        
        $lucro_produtors = [['Produtor', 'Valor']];
        foreach($graphs as $graph){
            array_push($lucro_produtors, [$graph->nome, intval($graph->valor)]);
        }        
        
        return view('painel.cadastro.lucro.index', compact('user', 'lucros', 'produtors', 'forma_pagamentos', 'search', 'lucro_produtors'));
    }


    public function create(Request $request)
    {
        if(Gate::denies('create_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();

        return view('painel.cadastro.lucro.create', compact('user', 'produtors'));
    }
 
    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $lucro = new Lucro();

            $lucro->cliente_id = $user->cliente->id;
            $lucro->produtor_id = $request->produtor;
            $lucro->forma_pagamento_id = $request->forma_pagamento;
            $lucro->data_lancamento = $request->data_lancamento;
            $lucro->valor = ($request->valor) ? str_replace(',', '.', $request->valor) : null;
            $lucro->observacao = $request->observacao;

            $lucro->save();

            // =================================================================
            // ARMAZENA O COMPROVANTE
            // =================================================================

            if ($request->path_comprovante) {

                $path_comprovante = 'documentos/'. $user->cliente->id . '/lucros/';

                $nome_arquivo = 'LUCRO_'.$lucro->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                $lucro->path_comprovante = $nome_arquivo;
                $lucro->save();

                Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Distribuição de Lucro foi criada com sucesso');
        }

        return redirect()->route('lucro.index');
    }

    public function show(Lucro $lucro, Request $request)
    {

        if(Gate::denies('edit_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $lucro->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Distribuição de Lucro não pertence ao cliente informado.');

            return redirect()->route('lucro.index');
        }


        $produtors = Produtor::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();        
        
        $forma_pagamentos = FormaPagamento::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->where('produtor_id', $lucro->produtor_id)
                            ->get();        
        

        return view('painel.cadastro.lucro.show', compact('user', 'lucro', 'produtors', 'forma_pagamentos'));
    }

    public function update(UpdateRequest $request, lucro $lucro)
    {

        if(Gate::denies('edit_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lucro->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Distribuição de Lucro não pertence ao cliente informado.');

            return redirect()->route('lucro.index');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $lucro->produtor_id = $request->produtor;
            $lucro->forma_pagamento_id = $request->forma_pagamento;
            $lucro->data_lancamento = $request->data_lancamento;
            $lucro->valor = ($request->valor) ? str_replace(',', '.', $request->valor) : null;
            $lucro->observacao = $request->observacao;

            $lucro->save();

            // =================================================================
            // ATUALIZA/ARMAZENA O COMPROVANTE
            // =================================================================

            if ($request->path_comprovante) {

                $path_comprovante = 'documentos/'. $user->cliente->id . '/lucros/';

                if($lucro->path_comprovante){
                    if(Storage::exists($path_comprovante)) {
                        Storage::delete($path_comprovante . $lucro->path_comprovante);
                    }
                }

                $nome_arquivo = 'LUCRO_'.$lucro->id.'.'.$request->path_comprovante->getClientOriginalExtension();
                $lucro->path_comprovante = $nome_arquivo;
                $lucro->save();

                Storage::putFileAs($path_comprovante, $request->file('path_comprovante'), $nome_arquivo);
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Distribuição de Lucro foi atualizada com sucesso');
        }

        return redirect()->route('lucro.index');
    }

    public function destroy(lucro $lucro, Request $request)
    {
        if(Gate::denies('delete_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        if($user->cliente->id != $lucro->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Distribuição de Lucro não pertence ao cliente informado.');

            return redirect()->route('lucro.index');
        }

        $message = '';

        try {
            DB::beginTransaction();

            $path_comprovante = 'documentos/'. $lucro->id . '/lucros/';
            if($lucro->path_comprovante){
                if(Storage::exists($path_comprovante)) {
                    Storage::delete($path_comprovante . $lucro->path_comprovante);
                }
            }            

            $lucro->delete();

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
            $request->session()->flash('message.content', 'A Distribuição de Lucro foi excluída com sucesso');
        }

        return redirect()->route('lucro.index');
    }

    public function download(lucro $lucro, Request $request){

        if(Gate::denies('view_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');
    
            return redirect()->route('painel');
        }

        if($user->cliente->id != $lucro->cliente_id){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Distribuição de Lucro não pertence ao cliente informado.');

            return redirect()->route('lucro.index');
        }

        $path_comprovante = 'documentos/' . $user->cliente->id . '/lucros/' . $lucro->path_comprovante;

        return Storage::download($path_comprovante);
    }

    public function refreshList(Request $request)
    {

        if(Gate::denies('view_lucro')){
            return redirect()->route('logout');
        }

        $user = Auth()->User();

        if(!$user->cliente){
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
            case 'FP': {

                if(!$request->has('produtor') || $request->produtor == null){
                    return response()->json(['mensagem' => []]);
                }

                $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                                    ->where('produtor_id', $request->produtor)
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
                $produtors = Produtor::where('cliente_id', $user->cliente->id)
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

        }

        return response()->json(['mensagem' => $mensagem]);
    }    

    public function search(Request $request)
    {

        if(Gate::denies('view_lucro')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $data_inicio = ($request->has('data_inicio') ? $request->data_inicio : null);
        $data_fim = ($request->has('data_fim') ? $request->data_fim : null);

        if(($data_inicio && $data_fim) && $data_inicio > $data_fim){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A data de início não pode ser maior que a data final.');

            return redirect()->route('lucro.index');
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

        if($request->has('data_inicio') ||
            $request->has('data_fim') ||
            $request->has('observacao') ||
            $request->has('produtor') ||
            $request->has('forma_pagamento')
        ){
            $search = [
                'data_inicio' => ($request->data_inicio) ? $request->data_inicio : '',
                'data_fim' => ($request->data_fim) ? $request->data_fim : '',
                'observacao' => ($request->observacao) ? $request->observacao : '',
                'produtor' => ($request->produtor) ? $request->produtor : '',
                'forma_pagamento' => ($request->forma_pagamento) ? $request->forma_pagamento : '',
            ];
        } else{
            $search = [];
        }      

        $lucros = Lucro::where('cliente_id', $user->cliente->id)
                        ->where(function($query) use ($search){

                            if($search && $search['produtor']){
                                $query->where('produtor_id', $search['produtor']);
                            }

                            if($search && $search['forma_pagamento']){
                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                            }

                            if($search && $search['observacao']){
                                $query->where('observacao', 'like', '%' . $search['observacao'] . '%');
                            }                                            

                            if($search && $search['data_inicio'] && $search['data_fim']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            } elseif($search && $search['data_inicio']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                            } elseif($search && $search['data_fim']){
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            }
                        })
                        ->orderBy('lucros.data_lancamento', 'desc')
                        ->get();

        $produtors = Produtor::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('nome', 'asc')
                                            ->get();      

        $forma_pagamentos = FormaPagamento::where('cliente_id', $user->cliente->id)
                                            ->where('status', 'A')
                                            ->orderBy('produtor_id')
                                            ->get();   
            
                                            
        $graphs = Lucro::where('lucros.cliente_id', $user->cliente->id)
                        ->join('produtors', 'lucros.produtor_id', '=', 'produtors.id')
                        ->where('produtors.cliente_id', $user->cliente->id)
                        ->where(function($query) use ($search){

                            if($search && $search['produtor']){
                                $query->where('produtor_id', $search['produtor']);
                            }

                            if($search && $search['forma_pagamento']){
                                $query->where('forma_pagamento_id', $search['forma_pagamento']);
                            }

                            if($search && $search['observacao']){
                                $query->where('observacao', 'like', '%' . $search['observacao'] . '%');
                            }                                            

                            if($search && $search['data_inicio'] && $search['data_fim']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            } elseif($search && $search['data_inicio']){
                                $query->where('data_lancamento', '>=', $search['data_inicio']);
                            } elseif($search && $search['data_fim']){
                                $query->where('data_lancamento', '<=', $search['data_fim']);
                            }
                        })
                        ->selectRaw('produtors.nome as nome, sum(lucros.valor) as valor')
                        ->groupBy('produtors.nome')
                        ->get();                                        
        
        $lucro_produtors = [['Produtor', 'Valor']];
        foreach($graphs as $graph){
            array_push($lucro_produtors, [$graph->nome, intval($graph->valor)]);
        }

        return view('painel.cadastro.lucro.index', compact('user', 'lucros', 'produtors', 'forma_pagamentos', 'search', 'lucro_produtors'));
    }

}
