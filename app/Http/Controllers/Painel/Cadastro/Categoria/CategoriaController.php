<?php

namespace App\Http\Controllers\Painel\Cadastro\Categoria;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Categoria\CreateRequest;
use App\Http\Requests\Cadastro\Categoria\UpdateRequest;
use Image;



class CategoriaController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Gate::denies('view_categoria')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $categorias_AT = Categoria::where('status','A')
                            ->orderBy('nome', 'asc')
                            ->get();


        $categorias_IN = Categoria::where('status','I')
                            ->orderBy('nome', 'asc')
                            ->get();


        return view('painel.cadastro.categoria.index', compact('user', 'categorias_AT', 'categorias_IN'));
    }




    public function create()
    {
        if(Gate::denies('create_categoria')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        return view('painel.cadastro.categoria.create', compact('user'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_categoria')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $categoria = new Categoria();

            $categoria->nome = $request->nome;
            $categoria->segmento = $request->segmento;
            if($request->segmento = 'MF'){
                $categoria->tipo = $request->tipo;
            }
            $categoria->status = $request->situacao;

            $categoria->save();

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
            $request->session()->flash('message.content', 'A Categoria <code class="highlighter-rouge">'. $categoria->nome .'</code> foi criada com sucesso');
        }

        return redirect()->route('categoria.index');
    }



    public function show(Categoria $categoria)
    {

        if(Gate::denies('edit_categoria')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        return view('painel.cadastro.categoria.show', compact('user', 'categoria'));
    }



    public function update(UpdateRequest $request, Categoria $categoria)
    {
        if(Gate::denies('edit_categoria')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $categoria->nome = $request->nome;
            $categoria->segmento = $request->segmento;
            if($request->segmento = 'MF'){
                $categoria->tipo = $request->tipo;
            }            
            $categoria->status = $request->situacao;

            $categoria->save();

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
            $request->session()->flash('message.content', 'A Categoria <code class="highlighter-rouge">'. $categoria->nome .'</code> foi alterada com sucesso');
        }

        return redirect()->route('categoria.index');
    }



    public function destroy(Categoria $categoria, Request $request)
    {
        if(Gate::denies('delete_categoria')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';
        $categoria_nome = $categoria->nome;

        try {
            DB::beginTransaction();

            $categoria->delete();

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
            $request->session()->flash('message.content', 'A Categoria <code class="highlighter-rouge">'. $categoria_nome .'</code> foi excluída com sucesso');
        }

        return redirect()->route('categoria.index');
    }

}
