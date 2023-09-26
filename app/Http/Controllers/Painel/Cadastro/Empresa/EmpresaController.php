<?php

namespace App\Http\Controllers\Painel\Cadastro\Empresa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Empresa;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Empresa\CreateRequest;
use App\Http\Requests\Cadastro\Empresa\UpdateRequest;
use Image;



class EmpresaController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_empresa')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $empresas_AT = Empresa::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        $empresas_IN = Empresa::where('status','I')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.empresa.index', compact('user', 'empresas_AT', 'empresas_IN'));
    }




    public function create(Request $request)
    {
        if(Gate::denies('create_empresa')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        return view('painel.cadastro.empresa.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_empresa')){
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

            $empresa = new Empresa();

            $empresa->cliente_id = $user->cliente->id;
            $empresa->nome = $request->nome;
            $empresa->tipo_pessoa = $request->tipo_pessoa;
            $empresa->cpf_cnpj = $request->cpf_cnpj;
            $empresa->status = $request->situacao;

            $empresa->save();

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'empresa_uk') !== false){
                $message = "Já existe um CPF/CNPJ com o mesmo valor informado.";

                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', $message);
    
                return redirect()->back()->withInput();

            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A empresa <code class="highlighter-rouge">'. $empresa->nome .'</code> foi criada com sucesso');
        }

        return redirect()->route('empresa.index');
    }



    public function show(Empresa $empresa, Request $request)
    {

        if(Gate::denies('edit_empresa')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $empresa->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Empresa não pertence ao cliente informado.');

            return redirect()->route('empresa.index');
        }

        return view('painel.cadastro.empresa.show', compact('user', 'empresa'));
    }



    public function update(UpdateRequest $request, Empresa $empresa)
    {
        if(Gate::denies('edit_empresa')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $empresa->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Empresa não pertence ao cliente informado.');

            return redirect()->route('empresa.index');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $empresa->nome = $request->nome;
            $empresa->tipo_pessoa = $request->tipo_pessoa;
            $empresa->cpf_cnpj = $request->cpf_cnpj;
            $empresa->status = $request->situacao;

            $empresa->save();

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();

            if(strpos($ex->getMessage(), 'empresa_uk') !== false){
                $message = "Já existe um CPF/CNPJ com o mesmo valor informado.";

                $request->session()->flash('message.level', 'warning');
                $request->session()->flash('message.content', $message);
    
                return redirect()->back()->withInput();

            } else{
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
            }
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Empresa <code class="highlighter-rouge">'. $empresa->nome .'</code> foi alterada com sucesso');
        }

        return redirect()->route('empresa.index');
    }



    public function destroy(Empresa $empresa, Request $request)
    {
        if(Gate::denies('delete_empresa')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente ||($user->cliente->id != $empresa->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A Empresa não pertence ao cliente informado.');

            return redirect()->route('empresa.index');
        }

        $message = '';
        $empresa_nome = $empresa->nome;

        try {
            DB::beginTransaction();

            $empresa->delete();

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
            $request->session()->flash('message.content', 'A Empresa <code class="highlighter-rouge">'. $empresa_nome .'</code> foi excluída com sucesso');
        }

        return redirect()->route('empresa.index');
    }

}