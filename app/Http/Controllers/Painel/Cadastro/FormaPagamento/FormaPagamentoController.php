<?php

namespace App\Http\Controllers\Painel\Cadastro\FormaPagamento;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Produtor;
use App\Models\FormaPagamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\FormaPagamento\CreateRequest;
use App\Http\Requests\Cadastro\FormaPagamento\UpdateRequest;
use Image;



class FormaPagamentoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_forma_pagamento')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $forma_pagamentos_AT = FormaPagamento::where('status','A')
                            ->where('cliente_id', $user->cliente_user->cliente->id)
                            ->orderBy('id', 'desc')
                            ->get();


        $forma_pagamentos_IN = FormaPagamento::where('status','I')
                            ->where('cliente_id', $user->cliente_user->cliente->id)
                            ->orderBy('id', 'desc')
                            ->get();


        return view('painel.cadastro.forma_pagamento.index', compact('user', 'forma_pagamentos_AT', 'forma_pagamentos_IN'));
    }


    public function create(Request $request)
    {
        if(Gate::denies('create_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $produtors = Produtor::where('status','A')
                            ->where('cliente_id', $user->cliente_user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();

        return view('painel.cadastro.forma_pagamento.create', compact('user', 'produtors'));
    }


    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $forma_pagamento = new FormaPagamento();

            if($request->tipo_conta == 'CC' || $request->tipo_conta == 'CP'){
                $forma_pagamento->tipo_conta = $request->tipo_conta;
            }

            $forma_pagamento->cliente_id = $user->cliente_user->cliente->id;
            $forma_pagamento->produtor_id = $request->produtor;
            //$forma_pagamento->tipo_conta = $request->tipo_conta;
            $forma_pagamento->banco = $request->banco;
            $forma_pagamento->agencia = $request->agencia;
            $forma_pagamento->conta = $request->conta;
            $forma_pagamento->pix = $request->pix;
            $forma_pagamento->status = $request->situacao;

            $forma_pagamento->save();

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
            $request->session()->flash('message.content', 'A Forma de Pagamento <code class="highlighter-rouge">'. $forma_pagamento->tipo_conta_texto .'</code> foi criada com sucesso');
        }

        return redirect()->route('forma_pagamento.index');
    }


    public function show(FormaPagamento $forma_pagamento, Request $request)
    {

        if(Gate::denies('view_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user || ($user->cliente_user->cliente->id != $forma_pagamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A forma de pagamento não pertence ao cliente informado.');

            return redirect()->route('forma_pagamento.index');
        }

        if($forma_pagamento->tipo_conta == 'CT' || $forma_pagamento->tipo_conta == 'ES'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não é possíve editar essa forma de pagamento.');

            return redirect()->route('forma_pagamento.index');
        }


        $produtors = Produtor::where('status','A')
                            ->where('cliente_id', $user->cliente_user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();

        return view('painel.cadastro.forma_pagamento.show', compact('user', 'forma_pagamento', 'produtors'));
    }



    public function update(UpdateRequest $request, FormaPagamento $forma_pagamento)
    {
        if(Gate::denies('edit_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user || ($user->cliente_user->cliente->id != $forma_pagamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A forma de pagamento não pertence ao cliente informado.');

            return redirect()->route('forma_pagamento.index');
        }

        if($forma_pagamento->tipo_conta == 'CT' || $forma_pagamento->tipo_conta == 'ES'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não é possíve editar essa forma de pagamento.');

            return redirect()->route('forma_pagamento.index');
        }


        $message = '';

        try {

            DB::beginTransaction();

            if(!$forma_pagamento->has_lancamento){
                if($forma_pagamento->tipo_conta == 'CC' || $forma_pagamento->tipo_conta == 'CP'){
                    $forma_pagamento->tipo_conta = $request->tipo_conta;
                }
                $forma_pagamento->produtor_id = $request->produtor;
            }
            $forma_pagamento->banco = $request->banco;
            $forma_pagamento->agencia = $request->agencia;
            $forma_pagamento->conta = $request->conta;
            $forma_pagamento->pix = $request->pix;
            $forma_pagamento->status = $request->situacao;

            $forma_pagamento->save();

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
            $request->session()->flash('message.content', 'A Forma de Pagamento <code class="highlighter-rouge">'. $forma_pagamento->tipo_conta_texto .'</code> foi alterada com sucesso');
        }

        return redirect()->route('forma_pagamento.index');
    }



    public function destroy(FormaPagamento $forma_pagamento, Request $request)
    {
        if(Gate::denies('delete_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user ||($user->cliente_user->cliente->id != $forma_pagamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A forma de pagamento não pertence ao cliente informado.');

            return redirect()->route('forma_pagamento.index');
        }

        if($forma_pagamento->tipo_conta == 'CT' || $forma_pagamento->tipo_conta == 'ES'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não é possíve editar essa forma de pagamento.');

            return redirect()->route('forma_pagamento.index');
        }
        

        $message = '';
        $forma_pagamento_nome = $forma_pagamento->tipo_conta_texto;

        try {
            DB::beginTransaction();

            $forma_pagamento->delete();

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
            $request->session()->flash('message.content', 'A Forma de Pagamento <code class="highlighter-rouge">'. $forma_pagamento_nome .'</code> foi excluída com sucesso');
        }

        return redirect()->route('forma_pagamento.index');
    }

    public function alterar_status(FormaPagamento $forma_pagamento, Request $request)
    {
        if(Gate::denies('edit_forma_pagamento')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente_user ||($user->cliente_user->cliente->id != $forma_pagamento->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A forma de pagamento não pertence ao cliente informado.');

            return redirect()->route('forma_pagamento.index');
        }

        if($forma_pagamento->tipo_conta == 'CT' || $forma_pagamento->tipo_conta == 'ES'){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não é possíve editar essa forma de pagamento.');

            return redirect()->route('forma_pagamento.index');
        }


        $message = '';

        try {
            DB::beginTransaction();

            $forma_pagamento->status = ($forma_pagamento->status == 'A') ? 'I' : 'A';

            $forma_pagamento->save();

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Forma de Pagamento <code class="highlighter-rouge">'. $forma_pagamento->tipo_conta_texto .'</code> foi alterada com sucesso');
        }

        return redirect()->route('forma_pagamento.index');
    }        

}
