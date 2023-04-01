<?php

namespace App\Http\Requests\Cadastro\Usuario;

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
            'email' => 'required|max:300|unique:users,email,'.$this->usuario->id, //Login de Acesso
            'situacao' => 'required',
            'password' => 'nullable|min:8',
            'password_confirm' => 'nullable|required_with:password|min:8|same:password',
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
            'situacao.required' => 'A situação é requerida',
            'password.min' => 'A Nova Senha deve conter no mínimo 8 caracteres',
            'password_confirm.min' => 'A Senha de Confirmação deve ter no mínimo 8 caracteres',
            'password_confirm.same' => 'A Senha de Confirmação deve ser igual a Nova Senha',
            'password_confirm.required_with' => 'A Senha de Confirmação deve ser igual a Nova Senha',
        ];
    }
}
