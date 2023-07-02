<?php

namespace App\Http\Requests\Cadastro\Googlemap;

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
            'valor_credito' => 'required',
            'valor_extra_apimaps' => 'required',
            'qtd_apimaps' => 'required',
            'valor_extra_geolocation' => 'required',
            'qtd_geolocation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'valor_credito.required' => 'O valor de crédito é requerido (em dólar)',
            'valor_extra_apimaps.required' => 'O valor extra após 1.000 visualizações da API Maps é requerido (em dólar)',
            'qtd_apimaps.required' => 'A quantidade de visualizações permitidas para a API Maps é requerida',
            'valor_extra_geolocation.required' => 'O valor extra após 1.000 acionamentos a Geolocalização é requerido',
            'qtd_geolocation.required' => 'A quantidade de acionamentos a Geolocalização é requerida',
        ];
    }
}
