<?php

namespace App\Http\Controllers\Painel\Cadastro\UsuarioCliente;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Cliente;
use App\Models\ClienteUser;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\UsuarioCliente\CreateRequest;
use App\Http\Requests\Cadastro\UsuarioCliente\UpdateRequest;
use Image;



class UsuarioClienteController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if(Gate::denies('view_usuario_cliente')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $cliente_users = ClienteUser::where('cliente_id',$user->cliente_user->cliente_id)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('painel.cadastro.usuario_cliente.index', compact('user', 'cliente_users'));
    }

    public function create()
    {
        if(Gate::denies('create_usuario_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $perfis = Perfil::all();

        return view('painel.cadastro.usuario_cliente.create', compact('user', 'perfis'));
    }

    public function store(CreateRequest $request)
    {
        if(Gate::denies('create_usuario_cliente')){
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

            $perfil = Role::where('name', 'Cliente')->first();

            $usuario->rolesAll()->attach($perfil);

            $status = $usuario->rolesAll()
                              ->withPivot(['status'])
                              ->first()
                              ->pivot;

            $status['status'] = 'A';
            $status->save();

            $newClienteUser = new ClienteUser();
            $newClienteUser->cliente_id = $user->cliente_user->cliente->id;
            $newClienteUser->user_id = $usuario->id;
            $newClienteUser->perfil_id = $request->perfil;
            $newClienteUser->save();            

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
            $request->session()->flash('message.content', 'O Usuário do Cliente <code class="highlighter-rouge">'. $usuario->name .'</code> foi criado com sucesso');
        }

        return redirect()->route('usuario_cliente.index');
    }

    public function show(ClienteUser $usuario_cliente)
    {

        if(Gate::denies('edit_usuario_cliente')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user = Auth()->User();

        $perfis = Perfil::all();

        return view('painel.cadastro.usuario_cliente.show', compact('user', 'usuario_cliente', 'perfis'));
    }

    public function update(UpdateRequest $request, ClienteUser $usuario_cliente)
    {
        if(Gate::denies('edit_usuario_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';

        try {

            DB::beginTransaction();

            $usuario_cliente->user->name = $request->nome;
            $usuario_cliente->user->email = $request->email;

            if($request->password){
                $usuario_cliente->user->password = bcrypt($request->password);
            }

            if($user->id != $usuario_cliente->user_id){
                $usuario_cliente->perfil_id = $request->perfil;
                $usuario_cliente->save();
            }

            $usuario_cliente->user->save();

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
            $request->session()->flash('message.content', 'O Usuário <code class="highlighter-rouge">'. $usuario_cliente->user->name .'</code> foi alterado com sucesso');
        }

        return redirect()->route('usuario_cliente.index');
    }

    public function destroy(ClienteUser $usuario_cliente, Request $request)
    {
        if(Gate::denies('delete_usuario_cliente')){
            abort('403', 'Página não disponível');
        }

        $user = Auth()->User();

        $message = '';
        $usuario_cliente_nome = $usuario_cliente->user->name;

        if(($usuario_cliente->user->id != $user->id)) {
            try {
                DB::beginTransaction();

                DB::table('role_user')->where('user_id', '=', $usuario_cliente->user->id)->delete();

                $usuario_cliente->delete();

                $usuario_cliente->user->delete();

                $path_avatar = 'images/avatar';

                if(\File::exists(public_path($path_avatar.'/'.$usuario_cliente->user->path_avatar))){
                    \File::delete(public_path($path_avatar.'/'.$usuario_cliente->user->path_avatar));
                }

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
            $request->session()->flash('message.content', 'O Usuário <code class="highlighter-rouge">'. $usuario_cliente_nome .'</code> foi excluído com sucesso');
        }

        return redirect()->route('usuario_cliente.index');
    }
}
