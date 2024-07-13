<?php

namespace App\Http\Requests\Cadastro\FormaPagamento;

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
            'tipo_conta' => 'required_if:liberado,OK',
            'banco' => 'max:200',
            'agencia' => 'max:50',
            'conta' => 'max:50',
            'pix' => 'max:255',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tipo_conta.required_if' => 'O tipo da conta é requerido',
            'banco.max' => 'O tamanho permitido para o banco é de 200 caracteres',
            'agencia.max' => 'O tamanho permitido para a agência é de 50 caracteres',
            'conta.max' => 'O tamanho permitido para a conta é de 50 caracteres',
            'pix.max' => 'O tamanho permitido para o pix é de 255 caracteres',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
