<?php

namespace App\Http\Requests\Cadastro\Categoria;

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
            'nome' => 'required|max:300|unique:categorias,nome,' . $this->categoria->id . ',id,segmento,'.$this->segmento,
            'segmento' => 'required',
            'tipo' => 'required_if:segmento,MF',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 300 caracteres',
            'nome.unique' => 'Já existe um nome/segmento com os valores informados',
            'segmento.required' => 'O Segmento é requerido',
            'tipo.required_if' => 'O Tipo de Movimento é requerido para Movimentações Fiscais',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
