<?php

namespace App\Http\Requests\Cadastro\Cliente;

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
            'cpf_cnpj' => Str::of($this->cpf_cnpj)->replaceMatches('/[^z0-9]++/', '')->__toString(),
            'telefone' => Str::of($this->telefone)->replaceMatches('/[^z0-9]++/', '')->__toString(),
            'end_cep' => Str::of($this->end_cep)->replaceMatches('/[^z0-9]++/', '')->__toString(),
        ]);

    }

    public function rules()
    {

        return [
            'tipo' => 'required',
            'situacao' => 'required',
            'nome' => 'required|max:200',
            'email' => 'required|email|max:255',
            'tipo_pessoa' => 'required',
            'cpf_cnpj' => 'required|cpf_ou_cnpj|digits_between:1,14|unique:clientes,cpf_cnpj',
            'telefone' => 'required',
            'end_cep' => 'required|digits_between:1,8',
            'end_cidade' => 'required|min:2|max:60',
            'end_uf' => 'required|min:2|max:2',
            'end_bairro' => 'required|max:60',
            'end_logradouro' => 'required|max:80',
            'end_numero' => 'required|max:20',
            'end_complemento' => 'max:40',
            'qtd_apimaps' => 'required|integer',
            'qtd_geolocation' => 'required|integer',
            'situacao' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'O Tipo é requerido',
            'situacao.required' => 'A Situação é requerida',
            'nome.required' => 'O Nome é requerido',
            'nome.max' => 'O tamanho permitido para o Nome é de 200 caracteres',
            'email.required' => 'O E-mail é requerido',
            'email.max' => 'O tamanho permitido para o E-mail é de 255 caracteres',
            'email.email' => 'O E-mail não é valido',
            'tipo_pessoa.required' => 'O tipo da pessoa é requerido',
            'cpf_cnpj.required' => 'O CPF/CNPJ é requerido',
            'cpf_cnpj.unique' => 'Já existe o CPF/CNPJ informado. Por gentileza, informe outro',
            'cpf_cnpj.digits_between' => 'O tamanho permitido para o CPF/CNPJ é de até 11/14 digitos. Outros caracteres não são permitidos',
            'cpf_cnpj.cpf_ou_cnpj' => 'O CPF/CNPJ não é válido',
            'telefone.required' => 'O Telefone é requerido',
            'end_cep.required' => 'O CEP é requerido',
            'end_cep.digits_between' => 'O tamanho permitido para o CEP é de até 8 digitos. Outros caracteres não são permitidos',
            'end_logradouro.required' => 'O Endereço é requerido',
            'end_logradouro.max' => 'O tamanho máximo permitido para o Endereço é de 80 caracteres',
            'end_numero.required' => 'O Número do Endereço é requerido',
            'end_numero.max' => 'O tamanho máximo permitido para o Número do Endereço é de 20 caracteres',
            'end_complemento.max' => 'O tamanho máximo permitido para o Complemento é de 40 caracteres',
            'end_bairro.required' => 'O Bairro é requerido',
            'end_bairro.max' => 'O tamanho máximo permitido para o Bairro é de 60 caracteres',
            'end_cidade.required' => 'A Cidade é requerida',
            'end_cidade.min' => 'O tamanho mínimo permitido para a Cidade é de 2 caracteres',
            'end_cidade.max' => 'O tamanho máximo permitido para a Cidade é de 60 caracteres',
            'end_uf.required' => 'O Estado é requerido',
            'end_uf.max' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'end_uf.min' => 'O tamanho permitido para o Estado é de 2 caracteres',
            'qtd_apimaps.required' => 'A quantidade de visualizações do Maps é requerida',
            'qtd_apimaps.integer' => 'A quantidade de buscas de geolocalização é requerida',
            'situacao.required' => 'A situação é requerida',
        ];
    }
}
