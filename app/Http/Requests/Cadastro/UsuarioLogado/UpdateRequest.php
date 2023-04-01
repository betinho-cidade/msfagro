<?php

namespace App\Http\Requests\Cadastro\UsuarioLogado;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class UpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|max:255',
            'email' => 'required|max:100|unique:users,email,'.$this->user->id, //Login de Acesso
            'password_new' => 'nullable|min:8',
            'password_confirm' => 'nullable|required_with:password_new|min:8|same:password_new',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 255 caracteres',
            'email.required' => 'O Login de Acesso é requerido',
            'email.max' => 'O tamanho permitido para o Login de Acesso é de 100 caracteres',
            'email.unique' => 'O Login de Acesso informado já existe',
            'password_new.min' => 'A Nova Senha deve conter no mínimo 8 caracteres',
            'password_confirm.min' => 'A Senha de Confirmação deve ter no mínimo 8 caracteres',
            'password_confirm.same' => 'A Senha de Confirmação deve ser igual a Nova Senha',
            'password_confirm.required_with' => 'A Senha de Confirmação deve ser igual a Nova Senha',
        ];
    }
}
