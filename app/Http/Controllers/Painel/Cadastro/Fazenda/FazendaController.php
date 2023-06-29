<?php

namespace App\Http\Controllers\Painel\Cadastro\Fazenda;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Fazenda;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Fazenda\CreateRequest;
use App\Http\Requests\Cadastro\Fazenda\UpdateRequest;
use Image;



class FazendaController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        if(Gate::denies('view_fazenda')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        $fazendas_AT = Fazenda::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        $fazendas_IN = Fazenda::where('status','I')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.fazenda.index', compact('user', 'fazendas_AT', 'fazendas_IN'));
    }




    public function create(Request $request)
    {
        if(Gate::denies('create_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o cliente.');

            return redirect()->route('painel');
        }

        return view('painel.cadastro.fazenda.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_fazenda')){
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

            $fazenda = new Fazenda();

            $fazenda->cliente_id = $user->cliente->id;
            $fazenda->nome = $request->nome;
            $fazenda->end_cep = $request->end_cep;
            $fazenda->end_cidade = $request->end_cidade;
            $fazenda->end_uf = $request->end_uf;
            $fazenda->latitude = $request->latitude;
            $fazenda->longitude = $request->longitude;
            $fazenda->qtd_macho = $request->qtd_macho;
            $fazenda->qtd_femea = $request->qtd_femea;
            $fazenda->status = $request->situacao;

            $fazenda->save();

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
            $request->session()->flash('message.content', 'A Fazenda <code class="highlighter-rouge">'. $fazenda->nome .'</code> foi criada com sucesso');
        }

        return redirect()->route('fazenda.index');
    }



    public function show(Fazenda $fazenda, Request $request)
    {

        if(Gate::denies('edit_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $fazenda->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A fazenda não pertence ao cliente informado.');

            return redirect()->route('fazenda.index');
        }

        return view('painel.cadastro.fazenda.show', compact('user', 'fazenda'));
    }



    public function update(UpdateRequest $request, Fazenda $fazenda)
    {
        if(Gate::denies('edit_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente || ($user->cliente->id != $fazenda->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A fazenda não pertence ao cliente informado.');

            return redirect()->route('fazenda.index');
        }

        $message = '';

        try {

            DB::beginTransaction();

            $fazenda->nome = $request->nome;
            $fazenda->end_cep = $request->end_cep;
            $fazenda->end_cidade = $request->end_cidade;
            $fazenda->end_uf = $request->end_uf;
            $fazenda->latitude = $request->latitude;
            $fazenda->longitude = $request->longitude;
            $fazenda->qtd_macho = $request->qtd_macho;
            $fazenda->qtd_femea = $request->qtd_femea;
            $fazenda->status = $request->situacao;

            $fazenda->save();

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
            $request->session()->flash('message.content', 'A Fazenda <code class="highlighter-rouge">'. $fazenda->nome .'</code> foi alterada com sucesso');
        }

        return redirect()->route('fazenda.index');
    }



    public function destroy(Fazenda $fazenda, Request $request)
    {
        if(Gate::denies('delete_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        if(!$user->cliente ||($user->cliente->id != $fazenda->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A fazenda não pertence ao cliente informado.');

            return redirect()->route('fazenda.index');
        }

        $message = '';
        $fazenda_nome = $fazenda->nome;

        try {
            DB::beginTransaction();

            $fazenda->delete();

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
            $request->session()->flash('message.content', 'A Fazenda <code class="highlighter-rouge">'. $fazenda_nome .'</code> foi excluída com sucesso');
        }

        return redirect()->route('fazenda.index');
    }


    function buscarGeolocalizacao() {
        //$endereco = "Rua Exemplo, Cidade, Estado, País";
        $endereco = 'Londrina, Paraná';

        //Formate a URL para fazer a solicitação à API de Geocodificação
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($endereco) . '&key=';

        // Faça a solicitação e obtenha a resposta em JSON
        $resposta = file_get_contents($url);

        // Analise a resposta JSON
        $dados = json_decode($resposta, true);

        // Verifique se houve um resultado válido
        if ($dados['status'] === 'OK') {
            // Obtenha a latitude e longitude do primeiro resultado
            $latitude = $dados['results'][0]['geometry']['location']['lat'];
            $longitude = $dados['results'][0]['geometry']['location']['lng'];

            dd('Latitude: ' . $latitude, ' Longitude: ' . $longitude);
        } else {
            // Se não houver resultados ou ocorrer um erro, exiba uma mensagem de erro
            dd('Erro: Não foi possível obter a latitude e longitude para o endereço especificado.');
        }
    }    

}
