<?php

namespace App\Http\Requests\Cadastro\Notificacao;

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
            'nome' => 'required|max:300',
            'resumo' => 'required|max:500',
            'data_inicio' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'hora_fim' => 'required|date_format:H:i',     
            'todos' => 'required',       
            'situacao' => 'required',
            'url_live' => 'url',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome é requerido',
            'nome.max' => 'O tamanho permitido para o nome é de 300 caracteres',
            'resumo.required' => 'O resumo da Notificação é requerido',
            'resumo.max' => 'O tamanho permitido para o resumo é de 500 caracteres',
            'todos.required' => 'A informação se a notificação será para todos os clientes é requerida',
            'situacao.required' => 'A situação é requerida',
            'data_inicio.required' => 'A data de início da Notificação é requerida',
            'data_inicio.date' => 'A data de início da Notificação não é válida',
            'hora_inicio.required' => 'O horário de início da Notificação é requerido',
            'hora_inicio.date_format' => 'O horário de início da Notificação não é válido',
            'data_fim.required' => 'A data final da Notificação é requerida',
            'data_fim.date' => 'A data final da Notificação não é válida',
            'data_fim.after_or_equal' => 'A data final não pode ser menor do que a data de início',
            'hora_fim.required' => 'O horário final da Notificação é requerido',
            'hora_fim.date_format' => 'O horário final da Notificação não é válido',
            'url_live.url' => 'A URL do Notificação não é válida',
        ];
    }
}
