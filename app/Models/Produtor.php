<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Produtor extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function forma_pagamentos(){

        return $this->hasMany('App\Models\FormaPagamento');
    }

    public function getTipoPessoaTextoAttribute()
    {
        $tipo_pessoa = '';

        switch($this->tipo_pessoa){
            case 'PF' : {
                $tipo_pessoa = 'Pessoa Física';
                break;
            }
            case 'PJ' : {
                $tipo_pessoa = 'Pessoa Jurídica';
                break;
            }
            default : {
                $tipo_pessoa = '---';
                break;
            }
        }

        return $tipo_pessoa;
    }

    public function getNomeProdutorAttribute()
    {
        $nome = $this->nome . ' / ' . $this->cpf_cnpj;
        $nome = Str::limit($nome, 100, '...');

        return $nome;
    }

    public function getNomeProdutorFullAttribute()
    {
        $nome = $this->nome . ' / ' . $this->cpf_cnpj;

        return $nome;
    }

}
