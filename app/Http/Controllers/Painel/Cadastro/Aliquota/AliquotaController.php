<?php

namespace App\Http\Controllers\Painel\Cadastro\Aliquota;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Aliquota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Aliquota\CreateRequest;
use App\Http\Requests\Cadastro\Aliquota\UpdateRequest;
use Image;



class AliquotaController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Gate::denies('view_aliquota')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $aliquotas = aliquota::orderBy('base_inicio', 'asc')
                            ->get();


        return view('painel.cadastro.aliquota.index', compact('user', 'aliquotas'));
    }




    public function create()
    {
        if(Gate::denies('create_aliquota')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        return view('painel.cadastro.aliquota.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_aliquota')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $aliquota = new Aliquota();

            $aliquota->base_inicio = $request->base_inicio;
            $aliquota->base_fim = $request->base_fim;
            $aliquota->aliquota = $request->aliquota;
            $aliquota->parcela_deducao = $request->parcela_deducao;
            $aliquota->user_id = $user->id;

            $aliquota->save();

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
            $request->session()->flash('message.content', 'A Alíquota <code class="highlighter-rouge">'. $aliquota->aliquota .' %</code> foi criada com sucesso');
        }

        return redirect()->route('aliquota.index');
    }



    public function show(Aliquota $aliquota)
    {

        if(Gate::denies('edit_aliquota')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        return view('painel.cadastro.aliquota.show', compact('user', 'aliquota'));
    }



    public function update(UpdateRequest $request, Aliquota $aliquota)
    {
        if(Gate::denies('edit_aliquota')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $aliquota->base_inicio = $request->base_inicio;
            $aliquota->base_fim = $request->base_fim;
            $aliquota->aliquota = $request->aliquota;
            $aliquota->parcela_deducao = $request->parcela_deducao;
            $aliquota->user_id = $user->id;

            $aliquota->save();

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
            $request->session()->flash('message.content', 'A Alíquota <code class="highlighter-rouge">'. $aliquota->aliquota .' %</code> foi alterada com sucesso');
        }

        return redirect()->route('aliquota.index');
    }



    public function destroy(Aliquota $aliquota, Request $request)
    {
        if(Gate::denies('delete_aliquota')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';
        $aliquota_nome = $aliquota->aliquota;

        try {
            DB::beginTransaction();

            $aliquota->delete();

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
            $request->session()->flash('message.content', 'A Alíquota <code class="highlighter-rouge">'. $aliquota_nome .' %</code> foi excluída com sucesso');
        }

        return redirect()->route('aliquota.index');
    }

}
