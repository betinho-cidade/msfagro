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
            'documento' => 'nullable|required_if:tipo,VD,CP',
            'path_documento' => 'nullable|required_if:tipo,VD,CP|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
            'path_comprovante' => ['nullable','mimes:jpeg,png,jpg,gif,svg,pdf', 'max:1024', new RangeValidation($this->data_programada)],
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O tipo de movimentação é requerido',
            'empresa.required_if' => 'A Empresa é requerida para Movimentações de Compra ou Venda',
            'documento.required_if' => 'O Número da Nota é requerido para Movimentações de Compra ou Venda',
            'path_documento.required_if' => 'O Arquivo da Nota Fiscal é requerido para Movimentações de Compra ou Venda',
            'path_documento.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para a Nota Fiscal',
            'path_documento.max' => 'O tamanho máximo permitido para a Nota Fiscal é de 1Mb.',
            'path_comprovante.mimes' => 'Somente imagens do tipo JPEG|JPG|PNG|GIF|SVG são permitidas para o comprovante de pagamento',
            'path_comprovante.max' => 'O tamanho máximo permitido para o comprovante de pagamento é de 1Mb.',
        ];
    }
}
