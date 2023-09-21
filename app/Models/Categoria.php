<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{

    public function efetivos()
    {
        return $this->hasMany('App\Models\Efetivo');
    }

    public function movimentacaos()
    {
        return $this->hasMany('App\Models\Movimentacao');
    }

    public function getNomeSegmentoAttribute()
    {
        $segmento = '';

        switch($this->segmento){
            case 'MG' : {
                $segmento = 'Movimentação Bovina';
                break;
            }
            case 'MF' : {
                $segmento = 'Movimentação Fiscal';
                break;
            }
            default : {
                $segmento = '---';
                break;
            }
        }

        return $segmento;
    }

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 15, '...');

        return $nome_reduzido;
    }

    public function getTipoCategoriaAttribute()
    {
        $tipo = '';

        switch($this->tipo){
            case 'D' : {
                $tipo = 'Despesa';
                break;
            }
            case 'R' : {
                $tipo = 'Receita';
                break;
            }
            default : {
                $tipo = '---';
                break;
            }
        }

        return $tipo;
    }


}
