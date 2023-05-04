<?php

namespace App\Http\Requests\Movimentacao\Lancamento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'cpf_cnpj' => Str::of($this->cpf_cnpj)->replaceMatches('/[^z0-9]++/', '')->__toString(),
    //         'telefone' => Str::of($this->telefone)->replaceMatches('/[^z0-9]++/', '')->__toString(),
    //         'end_cep' => Str::of($this->end_cep)->replaceMatches('/[^z0-9]++/', '')->__toString(),
    //     ]);
    // }

    public function rules()
    {

        return [
            'tipo' => 'required',
            'empresa' => 'nullable|required_if:tipo,VD,CP',
            'observacao' => 'max:5',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O tipo de movimentação é requerido',
            'empresa.required_if' => 'A Empresa é requerida para Lançamentos de Compra ou Venda',
            'observacao.max' => 'obs maior que 5',
        ];
    }
}
