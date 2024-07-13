<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
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

    public function efetivos(){

        return $this->hasMany('App\Models\Efetivo');
    }

    public function movimentacaos(){

        return $this->hasMany('App\Models\Movimentacao');
    }

    public function cliente_googlemaps(){

        return $this->hasMany('App\Models\ClienteGooglemap');
    }   
    
    public function cliente_notificacaos()
    {
        return $this->hasMany('App\Models\ClienteNotificacao');
    }    

    public function lucros(){

        return $this->hasMany('App\Models\Lucro');
    }    

    public function cliente_users(){
        return $this->hasMany('App\Models\ClienteUser');
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

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 15, '...');

        return $nome_reduzido;
    }

    public function getNomeClienteAttribute()
    {
        $nome_cliente = $this->nome . ' - ' . $this->tipo_pessoa. ':' . $this->cpf_cnpj;

        return $nome_cliente;
    }

    public function getNomeClienteReduzidoAttribute()
    {
        $nome_cliente_reduzido =  Str::limit($this->getNomeClienteAttribute(), 15, '...');

        return $nome_cliente_reduzido;
    }


    public function getSaldoGlobalAttribute(){

        $saldo_global = $this->movimentacaos()->where('movimentacaos.cliente_id', $this->id)
                                        ->where(function($query){
                                            if($this->tipo == 'AG'){
                                                $query->where('segmento', 'MF');
                                            }
                                        })
                                        ->select(DB::raw('SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) as receita,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) as despesa,
                                                SUM(CASE WHEN movimentacaos.tipo = (\'R\') THEN movimentacaos.valor ELSE 0 END) - SUM(CASE WHEN movimentacaos.tipo = (\'D\') THEN movimentacaos.valor ELSE 0 END) AS saldo'))
                                        ->first();      
        return $saldo_global->saldo ?? 0;  
    }    

}
