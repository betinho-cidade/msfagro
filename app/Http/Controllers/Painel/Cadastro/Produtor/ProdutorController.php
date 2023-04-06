<?php

namespace App\Http\Controllers\Painel\Cadastro\Produtor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Produtor;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Produtor\CreateRequest;
use App\Http\Requests\Cadastro\Produtor\UpdateRequest;
use Image;



class ProdutorController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_produtor')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $produtors_AT = produtor::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        $produtors_IN = produtor::where('status','I')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.produtor.index', compact('user', 'produtors_AT', 'produtors_IN'));
    }




    public function create(Request $request)
    {
        if(Gate::denies('create_produtor')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        return view('painel.cadastro.produtor.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_produtor')){
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

            $produtor = new produtor();

            $produtor->cliente_id = $user->cliente->id;
            $produtor->nome = $request->nome;
            $produtor->email = $request->email;
            $produtor->tipo_pessoa = $request->tipo_pessoa;
            $produtor->cpf_cnpj = $request->cpf_cnpj;
            $produtor->telefone = $request->telefone;
            $produtor->inscricao_estadual = $request->inscricao_estadual;
            $produtor->end_cep = $request->end_cep;
            $produtor->end_cidade = $request->end_cidade;
            $produtor->end_uf = $request->end_uf;
            $produtor->end_logradouro = $request->end_logradouro;
            $produtor->end_numero = $request->end_numero;
            $produtor->end_bairro = $request->end_bairro;
            $produtor->end_complemento = $request->end_complemento;
            $produtor->status = $request->situacao;

            $produtor->save();

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
            $request->session()->flash('message.content', 'O Produtor <code class="highlighter-rouge">'. $produtor->nome .'</code> foi criado com sucesso');
        }

        return redirect()->route('produtor.index');
    }



    public function show(Produtor $produtor, Request $request)
    {

        if(Gate::denies('edit_produtor')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $produtor->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Produtor não pertence ao cliente informado.');

            return redirect()->route('produtor.index');
        }

        return view('painel.cadastro.produtor.show', compact('user', 'produtor'));
    }



    public function update(UpdateRequest $request, Produtor $produtor)
    {
        if(Gate::denies('edit_produtor')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $produtor->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Produtor não pertence ao cliente informado.');

            return redirect()->route('produtor.index');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $produtor->nome = $request->nome;
            $produtor->email = $request->email;
            $produtor->tipo_pessoa = $request->tipo_pessoa;
            $produtor->cpf_cnpj = $request->cpf_cnpj;
            $produtor->telefone = $request->telefone;
            $produtor->inscricao_estadual = $request->inscricao_estadual;
            $produtor->end_cep = $request->end_cep;
            $produtor->end_cidade = $request->end_cidade;
            $produtor->end_uf = $request->end_uf;
            $produtor->end_logradouro = $request->end_logradouro;
            $produtor->end_numero = $request->end_numero;
            $produtor->end_bairro = $request->end_bairro;
            $produtor->end_complemento = $request->end_complemento;
            $produtor->status = $request->situacao;

            $produtor->save();

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
            $request->session()->flash('message.content', 'O Produtor <code class="highlighter-rouge">'. $produtor->nome .'</code> foi alterado com sucesso');
        }

        return redirect()->route('produtor.index');
    }



    public function destroy(produtor $produtor, Request $request)
    {
        if(Gate::denies('delete_produtor')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente ||($user->cliente->id != $produtor->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O Produtor não pertence ao cliente informado.');

            return redirect()->route('produtor.index');
        }

        $message = '';
        $produtor_nome = $produtor->nome;

        try {
            DB::beginTransaction();

            $produtor->delete();

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
            $request->session()->flash('message.content', 'O Produtor <code class="highlighter-rouge">'. $produtor_nome .'</code> foi excluído com sucesso');
        }

        return redirect()->route('produtor.index');
    }

}
