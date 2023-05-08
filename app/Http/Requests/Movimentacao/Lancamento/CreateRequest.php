<?php

namespace App\Http\Requests\Movimentacao\Lancamento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Rules\RangeValidation;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'tipo' => 'required',
            'categoria' => 'required',
            'produtor' => 'nullable|required_if:tipo,VD,CP',
            'data_programada' => 'required|date',
            'item_macho' => 'nullable|required_if:categoria,1,3',
            'qtd_macho' => 'nullable|required_if:categoria,1,3',
            'item_femea' => 'nullable|required_if:categoria,2,3',
            'qtd_femea' => 'nullable|required_if:categoria,2,3',
            'valor' => 'nullable|required_if:tipo,VD,CP',
            'origem' => 'nullable|required_if:tipo,VD,EG',
            'destino' => 'nullable|required_if:tipo,CP,EG',
            'forma_pagamento' => 'nullable|required_if:tipo,VD,CP',
            'empresa' => 'nullable|required_if:tipo,VD,CP',
            'nota' => 'nullable|required_if:tipo,VD,CP',
            'path_nota' => 'nullable|required_if:tipo,VD,CP|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
            'path_comprovante' => ['nullable','mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024', new RangeValidation($this->data_programada)],
            'gta' => 'max:50',
            'observacao' => 'max:1000',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O Tipo de Movimentação é requerido',
            'categoria.required_if' => 'A Categoria é requerida',
            'produtor.required' => 'A Produtor é requerido para Movimentações de Compra ou Venda',
            'data_programada.required' => 'A Data Programada é requerida',
            'data_programada.date' => 'A Data Programada é invalida',
            'item_macho.required_if' => 'A Classificação de Machos é requerida para a Categoria Macho ou Macho/Fêmea',
            'qtd_macho.required_if' => 'A Quantidade de Machos é requerida para a Categoria Macho ou Macho/Fêmea',
            'item_femea.required_if' => 'A Classificação de Fêmeas é requerida para a Categoria Macho ou Macho/Fêmea',
            'qtd_femea.required_if' => 'A Quantidade de Fêmeas é requerida para a Categoria Macho ou Macho/Fêmea',
            'valor.required_if' => 'O Valor é requerido para Movimentações de Compra ou Venda',
            'origem.required_if' => 'A Fazenda de Origem é requerida para Movimentações de Venda ou Engorda',
            'destino.required_if' => 'A Fazenda de Destino é requerida para Movimentações de Compra ou Engorda',
            'forma_pagamento.required_if' => 'A Forma de Pagamento é requerida para Movimentações de Compra ou Venda',
            'empresa.required_if' => 'A Empresa é requerida para Movimentações de Compra ou Venda',
            'nota.required_if' => 'O Número da Nota Fiscal é requerido para Movimentações de Compra ou Venda',
            'path_nota.required_if' => 'O Arquivo da Nota Fiscal é requerido para Movimentações de Compra ou Venda',
            'path_nota.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a Nota Fiscal',
            'path_nota.max' => 'O tamanho máximo permitido para a Nota Fiscal é de 1Mb.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante de pagamento',
            'path_comprovante.max' => 'O tamanho máximo permitido para o Comprovante de Pagamento é de 1Mb.',
            'gta.max' => 'O tamanho máximo permitido para o Número da GTA é de 50 caracteres.',
            'observacao.max' => 'O tamanho máximo permitido para a Observação é de 1.000 caracteres.',
        ];
    }
}
