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
            'tipo_conta' => 'required',
            'titular' => 'required|max:200',
            'doc_titular' => 'required|max:100',
            'banco' => 'max:200',
            'agencia' => 'max:50',
            'conta' => 'max:50',
            'pix' => 'required_if:tipo_conta,PX|max:255',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tipo_conta.required' => 'O tipo da conta é requerido',
            'titular.required' => 'O titular é requerido',
            'titular.max' => 'O tamanho permitido para o titular é de 200 caracteres',
            'doc_titular.required' => 'O documento do titular é requerido',
            'doc_titular.max' => 'O tamanho permitido para o documento do titular é de 100 caracteres',
            'banco.max' => 'O tamanho permitido para o banco é de 200 caracteres',
            'agencia.max' => 'O tamanho permitido para a agência é de 50 caracteres',
            'conta.max' => 'O tamanho permitido para a conta é de 50 caracteres',
            'pix.required_if' => 'Para o tipo de conta PIX, a informação do PIX é requerida',
            'pix.max' => 'O tamanho permitido para o pix é de 255 caracteres',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
