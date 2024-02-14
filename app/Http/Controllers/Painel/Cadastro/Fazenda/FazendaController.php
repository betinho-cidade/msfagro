<?php

namespace App\Http\Controllers\Painel\Cadastro\Fazenda;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Fazenda;
use App\Models\Cliente;
use App\Models\ClienteGooglemap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Fazenda\CreateRequest;
use App\Http\Requests\Cadastro\Fazenda\UpdateRequest;
use Image;
use Carbon\Carbon;



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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }

        $anomes_referencia = Carbon::now();
        $cliente_googlemap = ClienteGooglemap::where('cliente_id', $user->cliente->id)
                                             ->whereYear('anomes_referencia', $anomes_referencia->year)
                                             ->whereMonth('anomes_referencia', $anomes_referencia->month)
                                             ->first();       

        $cliente = Cliente::where('id', $user->cliente->id)                                     
                           ->first();    
        
        $qtd_apimaps = $cliente->qtd_apimaps;
        $qtd_geolocation = $cliente->qtd_geolocation;


        $fazendas_AT = Fazenda::where('status','A')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        $fazendas_IN = Fazenda::where('status','I')
                            ->where('cliente_id', $user->cliente->id)
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.fazenda.index', compact('user', 'fazendas_AT', 'fazendas_IN', 'cliente_googlemap', 'qtd_apimaps', 'qtd_geolocation'));
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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }        

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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }        

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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }             

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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }             

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

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }             

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


    function geomaps(Fazenda $fazenda, Request $request) {

        if(Gate::denies('view_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }        

        if(!$user->cliente || ($user->cliente->id != $fazenda->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A fazenda não pertence ao cliente informado.');

            return redirect()->route('fazenda.index');
        }

        $anomes_referencia = Carbon::now();
        $cliente_googlemap = ClienteGooglemap::where('cliente_id', $user->cliente->id)
                                             ->whereYear('anomes_referencia', $anomes_referencia->year)
                                             ->whereMonth('anomes_referencia', $anomes_referencia->month)
                                             ->first();       

        $cliente = Cliente::where('id', $user->cliente->id)                                     
                           ->first();        
                
        if( (!$cliente_googlemap && $cliente->qtd_geolocation == 0) ||
            ($cliente_googlemap && ($cliente->qtd_geolocation <= $cliente_googlemap->qtd_geolocation))){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'O limite mensal ('.$cliente->qtd_geolocation.') de busca de Latitude/Longitude foi atingido. Favor aguardar o próximo mês.');

            return redirect()->route('fazenda.index');
        }       

        if(!$fazenda->end_uf && (!$fazenda->end_cidade || !$fazenda->end_uf)){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'É necessário que o CEP ou a Cidade/UF estejam com valores armazenados.');

            return redirect()->route('fazenda.index');
        }        

        $endereco = urlencode($fazenda->end_cep . ',' . $fazenda->end_cidade . ',' . $fazenda->end_uf);

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $endereco . '&key='. env('API_KEY_GOOGLE');        

        $message = '';
        $latitude = '0';
        $longitude = '0';

        try {
            $resposta = file_get_contents($url);

            $dados = json_decode($resposta,true);

            if ($dados['status'] === 'OK') {
                $latitude = $dados['results'][0]['geometry']['location']['lat'];
                $longitude = $dados['results'][0]['geometry']['location']['lng'];
            } else {
                $message = 'Não foi possível obter a latitude e longitude para o endereço especificado.';
            } 
        } catch(Exception $ex){
            $message = 'Não foi possível obter a latitude e longitude para o endereço especificado. ' . $ex->getMessage();
        }

        if ($message == '' && $latitude != '0' && $longitude != '0') {
            try {

                DB::beginTransaction();
    
                $fazenda->latitude = $latitude;
                $fazenda->longitude = $longitude;
    
                $fazenda->save();

                if($cliente_googlemap){
                    $cliente_googlemap->qtd_geolocation = $cliente_googlemap->qtd_geolocation + 1;
                    
                    $cliente_googlemap->save();
                } else {
                    $new_cliente_googlemap = new ClienteGooglemap();

                    $new_cliente_googlemap->cliente_id = $user->cliente->id;
                    $new_cliente_googlemap->anomes_referencia = $anomes_referencia;
                    $new_cliente_googlemap->qtd_apimaps = 0;
                    $new_cliente_googlemap->qtd_geolocation = 1;

                    $new_cliente_googlemap->save();                    
                }
    
                DB::commit();
    
            } catch (Exception $ex){
    
                DB::rollBack();
    
                $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. " . $ex->getMessage();
            }
        } else {
            $message = "Erro desconhecido, não foi possível buscar a geolocalização";
        }

        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'A Latitude ('. $fazenda->latitude .') e Longitude ('. $fazenda->longitude .') da Fazenda <code class="highlighter-rouge">'. $fazenda->nome .'</code> foi alterada com sucesso.');
        }
        
        return redirect()->route('fazenda.index');
    }    



    public function alterar_status(Fazenda $fazenda, Request $request)
    {
        if(Gate::denies('edit_fazenda')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        // if($user->cliente->tipo == 'AG'){
        //     $request->session()->flash('message.level', 'warning');
        //     $request->session()->flash('message.content', 'Cadatro permitido somente para o perfil Pecuarista.');

        //     return redirect()->route('painel');
        // }             

        if(!$user->cliente ||($user->cliente->id != $fazenda->cliente_id) ){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'A fazenda não pertence ao cliente informado.');

            return redirect()->route('fazenda.index');
        }

        $message = '';

        try {
            DB::beginTransaction();

            $fazenda->status = ($fazenda->status == 'A') ? 'I' : 'A';

            $fazenda->save();

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
            $request->session()->flash('message.content', 'A Fazenda <code class="highlighter-rouge">'. $fazenda->nome .'</code> foi alterada com sucesso');
        }

        return redirect()->route('fazenda.index');
    }

}
