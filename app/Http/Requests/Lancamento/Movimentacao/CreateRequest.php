<?php

namespace App\Http\Requests\Lancamento\Movimentacao;

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
            'data_programada' => 'required|date',
            'data_pagamento' => 'nullable|required_with:path_comprovante|date',
            'valor' => 'required',
            'forma_pagamento' => 'required',
            'empresa' => 'required',
            'nota' => 'required|max:50',
            'item_texto' => 'required|max:300',
            'observacao' => 'max:1000',
            'path_nota' => 'required|mimes:jpeg,png,jpg,gif,svg,pdf|max:10240',
            'path_comprovante' => ['nullable', 'required_with:data_pagamento', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:5120', new RangeValidation($this->data_pagamento ?? '')],
            'path_anexo' => 'nullable|mimes:jpeg,png,jpg,gif,svg,pdf,xlsx,xls,doc,docx,xml|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'produtor.required' => 'O Produtor é requerido',
            'data_programada.required' => 'A Data Programada é requerida',
            'data_programada.date' => 'A Data Programada é invalida',
            'data_pagamento.date' => 'A Data Pagamento é invalida',
            'data_pagamento.required_with' => 'A Data Pagamento é requerida com o Comprovante de Pagamento',
            'valor.required' => 'O Valor é requerido',
            'forma_pagamento.required' => 'A Forma de Pagamento é requerida',
            'empresa.required' => 'A Empresa é requerida',
            'nota.required' => 'O Número da Nota Fiscal é requerida',
            'nota.max' => 'O tamanho máximo permitido para o Número da Nota Fiscal é de 50 caracteres.',
            'item_texto.required' => 'O Item Fiscal é requerido.',
            'item_texto.max' => 'O tamanho máximo permitido para o Item Fiscal é de 300 caracteres.',
            'observacao.max' => 'O tamanho máximo permitido para a Observação é de 1.000 caracteres.',
            'path_nota.required' => 'O Arquivo da Nota Fiscal é requerido',
            'path_nota.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a Nota Fiscal',
            'path_nota.max' => 'O tamanho máximo permitido para a Nota Fiscal é de 10Mb.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante de pagamento',
            'path_comprovante.max' => 'O tamanho máximo permitido para o Comprovante de Pagamento é de 5Mb.',
            'path_comprovante.required_with' => 'O Comprovante de Pagamento é requerido com a Data de Pagamento.',
            'path_anexo.mimes' => 'Somente arquivos do tipo JPEG|JPG|PNG|GIF|SVG|XLSX|XLS|DOC|DOCX|XML são permitidas para o Anexo',
            'path_anexo.max' => 'O tamanho máximo permitido para o Anexo é de 10Mb.',

        ];
    }
}
