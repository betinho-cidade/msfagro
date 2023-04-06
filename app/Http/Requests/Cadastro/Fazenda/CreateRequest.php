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

    public function rules()
    {
        return [
            'nome' => 'required|max:500',
            'end_cidade' => 'nullable|min:2|max:60',
            'end_uf' => 'nullable|min:2|max:2',
            'geolocalizacao' => 'required|max:1000',
            'qtd_macho' => 'required|integer',
            'qtd_femea' => 'required|integer',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 500 caracteres',
            'end_cidade.min' => 'O tamanho mínimo permitido para a Cidade é de 2 caracteres',
            'end_cidade.max' => 'O tamanho máximo permitido para a Cidade é de 60 caracteres',
            'end_uf.max' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'end_uf.min' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'geolocalizacao.required' => 'A Geolocalização é requerido',
            'geolocalizacao.max' => 'O tamanho permitido para a geolocalização é de 1000 caracteres',
            'qtd_macho.required' => 'A quantidade de machos é requerida',
            'qtd_macho.integer' => 'A quantidade de machos somente aceita números',
            'qtd_femea.required' => 'A quantidade de fêmeas é requerida',
            'qtd_femea.integer' => 'A quantidade de fêmeas somente aceita números',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
