<?php

namespace App\Http\Requests\Cadastro\Aliquota;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class CreateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'base_inicio' => str_replace(',', '.', str_replace('.', '', $this->base_inicio)),
            'base_fim' => str_replace(',', '.', str_replace('.', '', $this->base_fim)),
            'aliquota' => str_replace(',', '.', str_replace('.', '', $this->aliquota)),
            'parcela_deducao' => str_replace(',', '.', str_replace('.', '', $this->parcela_deducao)),
        ]);
    }


    public function rules()
    {

        return [
            'base_inicio' => 'required',
            'base_fim' => 'required',
            'aliquota' => 'required',
            'parcela_deducao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'base_inicio.required' => 'A informação sobre Base Início é requerida',
            'base_fim.required' => 'A informação sobre Base Fim é requerida',
            'aliquota.required' => 'A informação sobre Alíquota é requerida',
            'parcela_deducao.required' => 'A informação sobre Parcela de Dedução é requerida',
        ];
    }
}
