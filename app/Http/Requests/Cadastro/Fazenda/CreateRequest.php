<?php

namespace App\Http\Requests\Cadastro\Fazenda;

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
            'end_cep' => Str::of($this->end_cep)->replaceMatches('/[^z0-9]++/', '')->__toString(),
        ]);
    }

    public function rules()
    {
        return [
            'tipo_cliente' => 'required',
            'nome' => 'required|max:500',
            'end_cep' => 'nullable|digits_between:1,8',
            'end_cidade' => 'nullable|min:2|max:60',
            'end_uf' => 'nullable|min:2|max:2',
            'latitude' => 'max:300',
            'longitude' => 'max:300',
            'qtd_macho' => 'required_if:tipo_cliente,PE,AB|integer',
            'qtd_femea' => 'required_if:tipo_cliente,PE,AB|integer',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tipo_cliente.required' => 'Não foi possível identificar o tipo do cliente',
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 500 caracteres',
            'end_cep.digits_between' => 'O tamanho permitido para o CEP é de até 8 digitos. Outros caracteres não são permitidos',
            'end_cidade.min' => 'O tamanho mínimo permitido para a Cidade é de 2 caracteres',
            'end_cidade.max' => 'O tamanho máximo permitido para a Cidade é de 60 caracteres',
            'end_uf.max' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'end_uf.min' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'latitude.max' => 'O tamanho permitido para a latitude é de 300 caracteres',
            'longitude.max' => 'O tamanho permitido para a Longitude é de 300 caracteres',            
            'qtd_macho.required_if' => 'A quantidade de machos é requerida para Pecuaristas',
            'qtd_macho.integer' => 'A quantidade de machos somente aceita números',
            'qtd_femea.required_if' => 'A quantidade de fêmeas é requerida para Pecuaristas',
            'qtd_femea.integer' => 'A quantidade de fêmeas somente aceita números',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
