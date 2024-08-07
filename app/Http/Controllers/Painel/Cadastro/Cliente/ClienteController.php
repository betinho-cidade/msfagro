<?php

namespace App\Http\Controllers\Painel\Cadastro\Cliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Perfil;
use App\Models\Cliente;
use App\Models\ClienteUser;
use App\Models\Googlemap;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Cliente\CreateRequest;
use App\Http\Requests\Cadastro\Cliente\UpdateRequest;
use Image;



class ClienteController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Gate::denies('view_cliente')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $clientes_AT = Cliente::where('status','A')
                            ->orderBy('nome', 'asc')
                            ->get();


        $clientes_IN = Cliente::where('status','I')
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.cliente.index', compact('user', 'clientes_AT', 'clientes_IN'));
    }

    public function create()
    {
        if(Gate::denies('create_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        return view('painel.cadastro.cliente.create', compact('user'));
    }

    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $googlemap = Googlemap::first();

        $qtd_apimaps = Cliente::sum('qtd_apimaps') + $request->qtd_apimaps;
        $qtd_geolocation = Cliente::sum('qtd_geolocation') + $request->qtd_geolocation;

        if($qtd_apimaps > $googlemap->qtd_apimaps || $qtd_geolocation > $googlemap->qtd_geolocation){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'As quantidades informadas tanto para a API Maps quanto para o Geolocation extrapolam o total permitido.<br>
            <br>Parametrização: Api Maps('.$googlemap->qtd_apimaps.') Geolocation ('.$googlemap->qtd_geolocation.')
            <br>Total Clientes: Api Maps('.$qtd_apimaps.') Geolocation ('.$qtd_geolocation.')'
            );

            return redirect()->route('cliente.show', compact('cliente'));
        }             

        $message = '';

        try {

            DB::beginTransaction();

            $cliente = new Cliente();

            $cliente->tipo = $request->tipo;
            $cliente->nome = $request->nome;
            $cliente->email = $request->email;
            $cliente->tipo_pessoa = $request->tipo_pessoa;
            $cliente->cpf_cnpj = $request->cpf_cnpj;
            $cliente->telefone = $request->telefone;
            $cliente->end_cep = $request->end_cep;
            $cliente->end_cidade = $request->end_cidade;
            $cliente->end_uf = $request->end_uf;
            $cliente->end_bairro = $request->end_bairro;
            $cliente->end_logradouro = $request->end_logradouro;
            $cliente->end_numero = $request->end_numero;
            $cliente->end_complemento = $request->end_complemento;
            $cliente->qtd_apimaps = $request->qtd_apimaps;
            $cliente->qtd_geolocation = $request->qtd_geolocation;
            $cliente->status = $request->situacao;

            $cliente->save();

            $forma_pagamento_CT = new FormaPagamento();
            $forma_pagamento_CT->cliente_id = $cliente->id;
            $forma_pagamento_CT->tipo_conta = 'CT';
            $forma_pagamento_CT->status = 'A';                       
            $forma_pagamento_CT->save();

            $forma_pagamento_ES = new FormaPagamento();
            $forma_pagamento_ES->cliente_id = $cliente->id;
            $forma_pagamento_ES->tipo_conta = 'ES';
            $forma_pagamento_ES->status = 'A';                       
            $forma_pagamento_ES->save();            

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
            $request->session()->flash('message.content', 'O Cliente <code class="highlighter-rouge">'. $cliente->nome .'</code> foi criado com sucesso');
        }

        return redirect()->route('cliente.index');
    }

    public function show(Cliente $cliente)
    {

        if(Gate::denies('edit_cliente')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $cliente_users = ClienteUser::where('cliente_id', $cliente->id)->get();                             

        return view('painel.cadastro.cliente.show', compact('user', 'cliente', 'cliente_users'));
    }

    public function update(UpdateRequest $request, Cliente $cliente)
    {
        if(Gate::denies('edit_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $googlemap = Googlemap::first();

        $qtd_apimaps = Cliente::sum('qtd_apimaps') - $cliente->qtd_apimaps + $request->qtd_apimaps;
        $qtd_geolocation = Cliente::sum('qtd_geolocation') - $cliente->qtd_geolocation + $request->qtd_geolocation;

        if($qtd_apimaps > $googlemap->qtd_apimaps || $qtd_geolocation > $googlemap->qtd_geolocation){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'As quantidades informadas tanto para a API Maps quanto para o Geolocation extrapolam o total permitido.<br>
            <br>Parametrização: Api Maps('.$googlemap->qtd_apimaps.') Geolocation ('.$googlemap->qtd_geolocation.')
            <br>Total Clientes: Api Maps('.$qtd_apimaps.') Geolocation ('.$qtd_geolocation.')'
            );

            return redirect()->route('cliente.show', compact('cliente'));
        }        

        $message = '';

        try {

            DB::beginTransaction();

            $cliente->tipo = $request->tipo;
            $cliente->nome = $request->nome;
            $cliente->email = $request->email;
            $cliente->tipo_pessoa = $request->tipo_pessoa;
            $cliente->cpf_cnpj = $request->cpf_cnpj;
            $cliente->telefone = $request->telefone;
            $cliente->end_cep = $request->end_cep;
            $cliente->end_cidade = $request->end_cidade;
            $cliente->end_uf = $request->end_uf;
            $cliente->end_bairro = $request->end_bairro;
            $cliente->end_logradouro = $request->end_logradouro;
            $cliente->end_numero = $request->end_numero;
            $cliente->end_complemento = $request->end_complemento;
            $cliente->qtd_apimaps = $request->qtd_apimaps;
            $cliente->qtd_geolocation = $request->qtd_geolocation;            
            $cliente->status = $request->situacao;

            $cliente->save();

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
            $request->session()->flash('message.content', 'A cliente <code class="highlighter-rouge">'. $cliente->nome .'</code> foi alterada com sucesso');
        }

        return redirect()->route('cliente.index');
    }

    public function destroy(Cliente $cliente, Request $request)
    {
        if(Gate::denies('delete_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';
        $cliente_nome = $cliente->nome;

        try {
            DB::beginTransaction();

            $cliente->delete();

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
            $request->session()->flash('message.content', 'O cliente <code class="highlighter-rouge">'. $cliente_nome .'</code> foi excluído com sucesso');
        }

        return redirect()->route('cliente.index');
    }

    public function alterar_status(Cliente $cliente, Request $request)
    {
        if(Gate::denies('edit_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            $cliente->status = ($cliente->status == 'A') ? 'I' : 'A';

            $cliente->save();

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
            $request->session()->flash('message.content', 'O cliente <code class="highlighter-rouge">'. $cliente->nome .'</code> foi alterado com sucesso');
        }

        return redirect()->route('cliente.index');
    }

    public function user_create(Cliente $cliente)
    {

        if(Gate::denies('create_cliente_user')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                        ->where('role_user.status', 'A')
                        ->join('roles', 'role_user.role_id', '=', 'roles.id')
                        ->where('roles.name', 'Cliente')
                        ->whereNotExists(function($query) use ($cliente)
                        {
                            $query->select(DB::raw(1))
                                ->from('cliente_users')
                                ->whereRaw('cliente_users.user_id = users.id')
                                ->where('cliente_users.cliente_id', $cliente->id);
                        })
                        ->select('users.*')
                        ->get();

        $perfils = Perfil::all();

        return view('painel.cadastro.cliente.user_create', compact('user', 'cliente', 'users', 'perfils'));
    }    

    public function user_store(Request $request, Cliente $cliente)
    {

        if(Gate::denies('create_cliente_user')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            foreach($request->users as $usuario)
            {
                
                $newClienteUser = new ClienteUser();
                $newClienteUser->cliente_id = $cliente->id;
                $newClienteUser->user_id = $usuario;
                $newClienteUser->perfil_id = $request->perfil;
                $newClienteUser->save();
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();
            if(strpos($ex->getMessage(), 'cliente_user_uk') !== false){
                $message = "Um dos usuários informados já está registrado para esse cliente.";

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
            $request->session()->flash('message.content', 'Os usuários foram vinculados com sucesso');
        }

        return redirect()->route('cliente.show', compact('cliente'));
    }       

    public function user_destroy(Cliente $cliente, ClienteUser $cliente_user, Request $request)
    {
        if(Gate::denies('delete_cliente_user')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            ClienteUser::where('id', $cliente_user->id)
                            ->where('cliente_id', $cliente->id)
                            ->delete();

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
            $request->session()->flash('message.content', 'O Usuário foi desvinculado do cliente com sucesso');
        }

        return redirect()->route('cliente.show', compact('cliente'));
    }     

}
