<?php

namespace App\Http\Controllers\Painel\Cadastro\Notificacao;

use App\Http\Controllers\Controller;
use App\Models\Notificacao;
use App\Models\ClienteNotificacao;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Notificacao\CreateRequest;
use App\Http\Requests\Cadastro\Notificacao\UpdateRequest;
use Image;
use Carbon\Carbon;


class NotificacaoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Gate::denies('view_notificacao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $notificacaos_AT = Notificacao::where('status','A')->orderBy('data_inicio', 'desc')->get();
        $notificacaos_IN = Notificacao::where('status','I')->orderBy('data_fim', 'desc')->get();

        return view('painel.cadastro.notificacao.index', compact('user', 'notificacaos_AT', 'notificacaos_IN'));
    }




    public function create()
    {
        if(Gate::denies('create_notificacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        return view('painel.cadastro.notificacao.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_notificacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $data_inicio = Carbon::createFromDate($request->data_inicio . ' ' . $request->hora_inicio);
        $data_fim = Carbon::createFromDate($request->data_fim . ' ' . $request->hora_fim);

        if(($data_fim < $data_inicio)){
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', 'A data/hora final não pode ser menor do que a data/hora de início.');

            return redirect()->back()->withInput();
        }        

        $message = '';

        try {

            DB::beginTransaction();

            $notificacao = new Notificacao();

            $notificacao->nome = $request->nome;
            $notificacao->resumo = $request->resumo;
            $notificacao->url_notificacao = $request->url_notificacao;
            $notificacao->data_inicio =  $data_inicio;
            $notificacao->data_fim =  $data_fim;
            $notificacao->destaque = $request->destaque;
            $notificacao->todos = $request->todos;
            $notificacao->status = $request->situacao;

            $notificacao->save();

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
            $request->session()->flash('message.content', 'A Notificação foi criada com sucesso');
        }

        return redirect()->route('notificacao.index');
    }



    public function show(Notificacao $notificacao)
    {

        if(Gate::denies('view_notificacao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $cliente_notificacaos = ClienteNotificacao::where('notificacao_id', $notificacao->id)->get();

        return view('painel.cadastro.notificacao.show', compact('user', 'notificacao', 'cliente_notificacaos'));
    }



    public function update(UpdateRequest $request, Notificacao $notificacao)
    {
        if(Gate::denies('edit_notificacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $data_inicio = Carbon::createFromDate($request->data_inicio . ' ' . $request->hora_inicio);
        $data_fim = Carbon::createFromDate($request->data_fim . ' ' . $request->hora_fim);

        if(($data_fim < $data_inicio)){
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', 'A data/hora final não pode ser menor do que a data/hora de início.');

            return redirect()->back()->withInput();
        }            

        $message = '';

        try {

            DB::beginTransaction();

            $notificacao->nome = $request->nome;
            $notificacao->resumo = $request->resumo;
            $notificacao->url_notificacao = $request->url_notificacao;
            $notificacao->data_inicio =  $data_inicio;
            $notificacao->data_fim =  $data_fim;
            $notificacao->destaque = $request->destaque;
            $notificacao->todos = $request->todos;
            $notificacao->status = $request->situacao;

            $notificacao->save();

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
            $request->session()->flash('message.content', 'A Notificação foi alterada com sucesso');
        }

        return redirect()->route('notificacao.index');
    }



    public function destroy(Notificacao $notificacao, Request $request)
    {
        if(Gate::denies('delete_notificacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            $notificacao->delete();

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
            $request->session()->flash('message.content', 'A Notificação foi excluída com sucesso');
        }

        return redirect()->route('notificacao.index');
    }


    public function cliente_create(Notificacao $notificacao)
    {

        if(Gate::denies('create_notificacao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $clientes = cliente::where('clientes.status', 'A')
                        ->whereNotExists(function($query) use ($notificacao)
                        {
                            $query->select(DB::raw(1))
                                ->from('cliente_notificacaos')
                                ->whereRaw('cliente_notificacaos.cliente_id = clientes.id')
                                ->where('cliente_notificacaos.notificacao_id', $notificacao->id);
                        })
                        ->get();

        return view('painel.cadastro.notificacao.cliente_create', compact('user', 'notificacao', 'clientes'));
    }    


    public function cliente_store(Request $request, Notificacao $notificacao)
    {

        if(Gate::denies('create_notificacao')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            foreach($request->clientes as $cliente)
            {
                $newClienteNotificacao = new ClienteNotificacao();
                $newClienteNotificacao->notificacao_id = $notificacao->id;
                $newClienteNotificacao->cliente_id = $cliente;
                $newClienteNotificacao->save();
            }

            DB::commit();

        } catch (Exception $ex){

            DB::rollBack();
            if(strpos($ex->getMessage(), 'cliente_notificacao_uk') !== false){
                $message = "Um dos clientes informados já está registrado na Notificação.";

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
            $request->session()->flash('message.content', 'Os clientes foram vinculados com sucesso');
        }

        return redirect()->route('notificacao.show', compact('notificacao'));
    }       

    public function cliente_destroy(Notificacao $notificacao, ClienteNotificacao $cliente_notificacao, Request $request)
    {
        if(Gate::denies('delete_notificacao')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {
            DB::beginTransaction();

            $cliente_notificacao = ClienteNotificacao::where('id', $cliente_notificacao->id)
                                    ->where('notificacao_id', $notificacao->id)
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
            $request->session()->flash('message.content', 'O cliente foi desvinculado da Notificação com sucesso');
        }

        return redirect()->route('notificacao.show', compact('notificacao'));
    }    

}
