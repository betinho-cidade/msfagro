<?php

namespace App\Http\Requests\Lancamento\Efetivo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Rules\EstoqueValidation;
use App\Rules\RangeValidation;

class UpdateRequest extends FormRequest
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
            'tipo' => 'required',
            'data_programada' => 'required|date',
            'data_pagamento' => 'nullable|required_with:path_comprovante|date',
            'valor' => 'nullable|required_if:tipo,VD,CP',
            'nota' => 'nullable|required_if:tipo,VD,CP',
            'gta' => 'required|max:50',
            'observacao' => 'max:1000',
            'path_nota' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf|max:10240',
            'path_gta' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf|max:5120',
            'path_comprovante' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:5120', new RangeValidation($this->data_pagamento ?? '')],
            'path_anexo' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf,xlsx,xls,doc,docx,xml|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O Tipo de Movimentação é requerido',
            'data_programada.required' => 'A Data Programada é requerida',
            'data_programada.date' => 'A Data Programada é invalida',
            'data_pagamento.date' => 'A Data Pagamento é invalida',
            'data_pagamento.required_with' => 'A Data Pagamento é requerida com o Comprovante de Pagamento',
            'valor.required_if' => 'O Valor é requerido para Movimentações de Compra ou Venda',
            'nota.required_if' => 'O Número da Nota Fiscal é requerido para Movimentações de Compra ou Venda',
            'gta.required' => 'O Número da GTA é requerido',
            'gta.max' => 'O tamanho máximo permitido para o Número da GTA é de 50 caracteres.',
            'observacao.max' => 'O tamanho máximo permitido para a Observação é de 1.000 caracteres.',
            'path_nota.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a Nota Fiscal',
            'path_nota.max' => 'O tamanho máximo permitido para a Nota Fiscal é de 10Mb.',
            'path_gta.required' => 'A GTA é requerida.',
            'path_gta.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a GTA',
            'path_gta.max' => 'O tamanho máximo permitido para a GTA é de 5Mb.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante de pagamento',
            'path_comprovante.max' => 'O tamanho máximo permitido para o Comprovante de Pagamento é de 5Mb.',
            'path_anexo.mimes' => 'Somente arquivos do tipo JPEG|JPG|PNG|GIF|SVG|XLSX|XLS|DOC|DOCX|XML são permitidas para o Anexo',
            'path_anexo.max' => 'O tamanho máximo permitido para o Anexo é de 10Mb.',
        ];
    }

}
