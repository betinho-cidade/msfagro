<?php

namespace App\Http\Controllers\Painel\Cadastro\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\Usuario\CreateRequest;
use App\Http\Requests\Cadastro\Usuario\UpdateRequest;
use Image;



class UsuarioController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Gate::denies('view_usuario')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $users_AT = User::join('role_user', 'role_user.user_id', 'users.id')
                            ->where('role_user.status','A')
                            ->orderBy('users.id', 'desc')
                            ->select('users.*')
                            ->get();


        $users_IN = User::join('role_user', 'role_user.user_id', 'users.id')
                            ->where('role_user.status','I')
                            ->orderBy('users.id', 'desc')
                            ->select('users.*')
                            ->get();


        return view('painel.cadastro.usuario.index', compact('user', 'users_AT', 'users_IN'));
    }




    public function create()
    {
        if(Gate::denies('create_usuario')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $perfis = Role::all();

        return view('painel.cadastro.usuario.create', compact('user', 'perfis'));
    }



    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_usuario')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $usuario = new User();

            $usuario->name = $request->nome;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);

            $usuario->save();

            $usuario->rolesAll()->attach($request->perfil);

            $status = $usuario->rolesAll()
                              ->withPivot(['status'])
                              ->first()
                              ->pivot;

            $status['status'] = $request->situacao;
            $status->save();

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
            $request->session()->flash('message.content', 'O Usuário <code class="highlighter-rouge">'. $usuario->name .'</code> foi criado com sucesso');
        }

        return redirect()->route('usuario.index');
    }



    public function show(User $usuario)
    {

        if(Gate::denies('edit_usuario')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $perfis = Role::all();

        return view('painel.cadastro.usuario.show', compact('user', 'usuario', 'perfis'));
    }



    public function update(UpdateRequest $request, User $usuario)
    {
        if(Gate::denies('edit_usuario')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $usuario->name = $request->nome;
            $usuario->email = $request->email;

            if($request->password){
                $usuario->password = bcrypt($request->password);
            }

            $usuario->save();

            $usuario_status = $usuario->situacao;

            if($request->situacao && ($request->situacao != $usuario_status['status']) && ($usuario->id != $user->id)){
                $usuario_status['status'] = $request->situacao;
                $usuario_status->save();
            }

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
            $request->session()->flash('message.content', 'O Usuário <code class="highlighter-rouge">'. $usuario->name .'</code> foi alterado com sucesso');
        }

        return redirect()->route('usuario.index');
    }



    public function destroy(User $usuario, Request $request)
    {
        if(Gate::denies('delete_usuario')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';
        $usuario_nome = $usuario->name;

        if(($usuario->id != $user->id)) {
            try {
                DB::beginTransaction();

                // $cliente = Cliente::where('user_id', '=', $usuario->id)
                //                   ->first();

                // if($cliente){
                //     $cliente->user_id = null;
                //     $cliente->save();
                // }

                DB::table('role_user')->where('user_id', '=', $usuario->id)->delete();

                $usuario->delete();

                DB::commit();

            } catch (Exception $ex){

                DB::rollBack();

                if(strpos($ex->getMessage(), 'Integrity constraint violation') !== false){
                    $message = "Não foi possível excluir o registro, pois existem referências ao mesmo em outros processos.";
                } else{
                    $message = "Erro desconhecido, por gentileza, entre em contato com o administrador. ".$ex->getMessage();
                }

            }
        } else {
            $message = "Não é possível excluir o usuário logado.";
        }


        if ($message && $message !='') {
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', $message);
        } else {
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'O Usuário <code class="highlighter-rouge">'. $usuario_nome .'</code> foi excluído com sucesso');
        }

        return redirect()->route('usuario.index');
    }

}
