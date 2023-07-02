<?php

namespace App\Http\Controllers\Painel\Cadastro\Googlemap;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Googlemap;
use App\Models\ClienteGooglemap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Googlemap\UpdateRequest;
use Image;
use Carbon\Carbon;


class GooglemapController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if(Gate::denies('view_googlemap')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $cliente_googlemaps = ClienteGooglemap::groupBy(DB::raw('concat(LPAD(MONTH(anomes_referencia), 2, 0), \'-\', YEAR(anomes_referencia))'))
                                        ->select(DB::raw('concat(YEAR(anomes_referencia), LPAD(MONTH(anomes_referencia), 2, 0)) AS mes_ordem,
                                                concat(LPAD(MONTH(anomes_referencia), 2, 0), \'-\', YEAR(anomes_referencia)) AS mes_referencia,
                                                SUM(qtd_apimaps) AS qtd_apimaps,
                                                SUM(qtd_geolocation) AS qtd_geolocation'))
                                        ->orderBy('anomes_referencia', 'desc')
                                        ->get();

        return view('painel.cadastro.googlemap.index', compact('user', 'cliente_googlemaps'));
    }

    public function list(Request $request)
    {
        if(Gate::denies('view_googlemap')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $mes_referencia = ($request->has('mes_referencia') ? $request->mes_referencia : null);

        if(!$mes_referencia){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Não foi possível associar o mês de referência informado.');

            return redirect()->route('googlemap.index');
        }

        $data_programada_vetor = explode('-', $mes_referencia);

        setlocale(LC_ALL, 'pt_BR.utf-8', 'ptb', 'pt_BR', 'portuguese-brazil', 'portuguese-brazilian', 'bra', 'brazil', 'br');
        $data_programada = Carbon::createFromDate($data_programada_vetor[1], $data_programada_vetor[0])->formatLocalized('%B/%Y');

        $cliente_googlemaps = ClienteGooglemap::whereYear('anomes_referencia', $data_programada_vetor[1])
                                        ->whereMonth('anomes_referencia', $data_programada_vetor[0])
                                        ->orderBy('anomes_referencia', 'desc')
                                        ->get();

        return view('painel.cadastro.googlemap.list', compact('user', 'cliente_googlemaps', 'mes_referencia', 'data_programada'));
    }



    public function show(Googlemap $googlemap)
    {

        if(Gate::denies('edit_googlemap')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        return view('painel.cadastro.googlemap.show', compact('user', 'googlemap'));
    }



    public function update(UpdateRequest $request, Googlemap $googlemap)
    {
        if(Gate::denies('edit_googlemap')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $credito = $request->valor_credito;
        $valor_api = (($request->qtd_apimaps / 1000) * $request->valor_extra_apimaps);
        $valor_geo = (($request->qtd_geolocation / 1000) * $request->valor_extra_geolocation);

        if(($valor_api + $valor_geo) > $credito){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'As quantidades informadas tanto para a API Maps quanto para o Geolocation extrapolam o valor de U$ '.$credito.' permitidos.<br>Api (U$ '.$valor_api.') Geolocation (U$ '.$valor_geo.') somando U$ '.($valor_api + $valor_geo));

            return redirect()->route('googlemap.show', compact('googlemap'));
        }        

        $message = '';

        try {

            DB::beginTransaction();

            $googlemap->valor_credito = $request->valor_credito;
            $googlemap->valor_extra_apimaps = $request->valor_extra_apimaps;
            $googlemap->qtd_apimaps = $request->qtd_apimaps;
            $googlemap->valor_extra_geolocation = $request->valor_extra_geolocation;
            $googlemap->qtd_geolocation = $request->qtd_geolocation;

            $googlemap->save();

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
            $request->session()->flash('message.content', 'Os dados da Visualização Google Maps foram alterdos com sucesso');
        }

        return redirect()->route('googlemap.show', compact('googlemap'));
    }

}
