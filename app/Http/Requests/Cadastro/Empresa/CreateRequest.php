<?php

namespace App\Http\Requests\Cadastro\Empresa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {

        $this->merge([
            'cpf_cnpj' => Str::of($this->cpf_cnpj)->replaceMatches('/[^z0-9]++/', '')->__toString(),
        ]);
    }

    public function rules()
    {
        return [
            'nome' => 'required|max:500',
            'tipo_pessoa' => 'required',
            'cpf_cnpj' => 'required|cpf_ou_cnpj|max:14',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 500 caracteres',
            'tipo_pessoa.required' => 'O tipo da pessoa é requerido',
            'cpf_cnpj.required' => 'O CPF/CNPJ é requerido',
            'cpf_cnpj.max' => 'O tamanho permitido para o CPF/CNPJ é de 14 caracteres',
            'cpf_cnpj.cpf_ou_cnpj' => 'O CPF/CNPJ não é válido',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
