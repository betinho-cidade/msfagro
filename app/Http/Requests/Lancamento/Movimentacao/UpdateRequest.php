<?php

namespace App\Http\Requests\Lancamento\Movimentacao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Rules\RangeValidation;

class UpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'data_programada' => 'required|date',
            'data_pagamento' => 'nullable|required_with:path_comprovante|date',
            'valor' => 'required',
            'empresa' => 'required',
            'nota' => 'required|max:50',
            'item_texto' => 'required|max:300',
            'observacao' => 'max:1000',
            'path_nota' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
            'path_comprovante' => ['nullable','mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024', new RangeValidation($this->data_pagamento ?? '')],
        ];
    }

    public function messages()
    {
        return [
            'data_programada.required' => 'A Data Programada é requerida',
            'data_programada.date' => 'A Data Programada é invalida',
            'data_pagamento.date' => 'A Data Pagamento é invalida',
            'data_pagamento.required_with' => 'A Data Pagamento é requerida com o Comprovante de Pagamento',
            'valor.required' => 'O Valor é requerido',
            'empresa.required' => 'A Empresa é requerida',
            'nota.required' => 'O Número da Nota Fiscal é requerida',
            'nota.max' => 'O tamanho máximo permitido para o Número da Nota Fiscal é de 50 caracteres.',
            'item_texto.required' => 'O Item Fiscal é requerido.',
            'item_texto.max' => 'O tamanho máximo permitido para o Item Fiscal é de 300 caracteres.',
            'observacao.max' => 'O tamanho máximo permitido para a Observação é de 1.000 caracteres.',
            'path_nota.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a Nota Fiscal',
            'path_nota.max' => 'O tamanho máximo permitido para a Nota Fiscal é de 1Mb.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante de pagamento',
            'path_comprovante.max' => 'O tamanho máximo permitido para o Comprovante de Pagamento é de 1Mb.',
        ];
    }

}
