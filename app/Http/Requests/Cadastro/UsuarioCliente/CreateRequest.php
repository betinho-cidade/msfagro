<?php

namespace App\Http\Requests\Cadastro\UsuarioCliente;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|max:255',
            'email' => 'required|max:300|unique:users', //Login de Acesso
            'perfil' => 'required',
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 255 caracteres',
            'email.required' => 'O Login de Acesso é requerido',
            'email.max' => 'O tamanho permitido para o Login de Acesso é de 300 caracteres',
            'email.unique' => 'O Login de Acesso informado já existe',
            'perfil.required' => 'O perfil é requerido',
            'password.required' => 'A Senha é requerida',
            'password.min' => 'A Senha deve conter no mínimo 8 caracteres',
            'password_confirm.required' => 'A Senha de Confirmação é requerida',
            'password_confirm.same' => 'A Senha de Confirmação deve ser igual a Senha',
        ];
    }
}
