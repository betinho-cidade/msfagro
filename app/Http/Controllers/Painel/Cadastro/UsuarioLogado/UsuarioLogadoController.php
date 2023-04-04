<?php

namespace App\Http\Controllers\Painel\Cadastro\UsuarioLogado;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Cadastro\UsuarioLogado\UpdateRequest;
use Image;
use Illuminate\Support\Facades\Hash;


class UsuarioLogadoController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }


    public function show(User $user)
    {

        if(Gate::denies('view_usuario_logado')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user_logado = Auth()->User();

        if($user->id != $user_logado->id){
            abort('403', 'Página não disponível');
        }

        return view('painel.cadastro.usuario_logado.show', compact('user'));
    }



    public function update(UpdateRequest $request, User $user)
    {

        if(Gate::denies('view_usuario_logado')){
            abort('403', 'Página não disponível');
            //return redirect()->back();
        }

        $user_logado = Auth()->User();

        if($user->id != $user_logado->id){
            abort('403', 'Página não disponível');
        }

        $message = '';

        try {

            DB::beginTransaction();

            if($request->view_password){
                if(Hash::check($request->view_password, $user->password)){
                    if($request->password_new){
                        $user->password = bcrypt($request->password_new);
                    }
                }
                else{
                    DB::rollBack();
                    $message = 'A Senha Atual não confere';

                    $request->session()->flash('message.level', 'danger');
                    $request->session()->flash('message.content', $message);

                    return redirect()->route('usuario_logado.show', compact('user'));
                };
            }

            $user->name = $request->nome;
            $user->email = $request->email;

            $user->save();

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
            $request->session()->flash('message.content', 'Seus dados foram atualizados com sucesso');
        }

        return redirect()->route('usuario_logado.show', compact('user'));
    }

}
