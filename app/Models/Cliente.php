<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Cliente extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function forma_pagamentos(){

        return $this->hasMany('App\Models\FormaPagamento');
    }

    public function fazendas(){

        return $this->hasMany('App\Models\Fazenda');
    }

    public function empresas(){

        return $this->hasMany('App\Models\Empresa');
    }

    public function produtors(){

        return $this->hasMany('App\Models\Produtor');
    }

    public function getTipoClienteAttribute()
    {
        $tipo = '';

        switch($this->tipo){
            case 'AG' : {
                $tipo = 'Agricultor';
                break;
            }
            case 'PE' : {
                $tipo = 'Pecuarista';
                break;
            }
            case 'AB' : {
                $tipo = 'Ambos';
                break;
            }
            default : {
                $tipo = '---';
                break;
            }
        }

        return $tipo;
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

}
