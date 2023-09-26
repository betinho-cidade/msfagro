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

    public function movimentacaos(){

        return $this->hasMany('App\Models\Movimentacao');
    }

    public function forma_pagamentos(){

        return $this->hasMany('App\Models\FormaPagamento');
    }

    public function lucros(){

        return $this->hasMany('App\Models\Lucro');
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

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 37, '...');

        return $nome_reduzido;
    }

    public function getNomeProdutorAttribute()
    {
        $nome_produtor = $this->nome . ' / ' . $this->cpf_cnpj;

        return $nome_produtor;
    }


    public function getNomeProdutorReduzidoAttribute()
    {
        $nome_produtor_reduzido =  Str::limit($this->getNomeProdutorAttribute(), 15, '...');

        return $nome_produtor_reduzido;
    }

    public function getEmailReduzidoAttribute()
    {
        $email_reduzido =  Str::limit($this->email, 15, '...');

        return $email_reduzido;
    }

}
