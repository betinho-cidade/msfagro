<?php

namespace App\Http\Requests\Cadastro\Lucro;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Rules\RangeValidation;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {

        $this->merge([
            'valor' => ( \IntlChar::ord(Str::substr(str_replace('R$', '', $this->valor), 0, 1)) == 160) 
                        ? str_replace(',', '.', str_replace('.', '', Str::substr($this->valor, 3)))
                        : str_replace(',', '.', str_replace('.', '', str_replace('R$', '', $this->valor))),
        ]);
    }    

    public function rules()
    {

        return [
            'produtor' => 'required',
            'data_lancamento' => 'required|date',
            'valor' => 'required',
            'forma_pagamento' => 'required',
            'observacao' => 'max:1000',
            'path_comprovante' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'produtor.required' => 'O Produtor é requerido',
            'data_lancamento.required' => 'A Data de Lançamento é requerida',
            'data_lancamento.date' => 'A Data de Lançamento é invalida',
            'valor.required' => 'O Valor é requerido',
            'forma_pagamento.required' => 'A Forma de Pagamento é requerida',
            'observacao.max' => 'O tamanho máximo permitido para a Observação é de 1.000 caracteres.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante',
            'path_comprovante.max' => 'O tamanho máximo permitido para o Comprovante é de 1Mb.',
        ];
    }
}
