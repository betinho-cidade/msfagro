<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Aviso extends Model
{

    public function movimentacao(){

        return $this->belongsTo('App\Models\Movimentacao');
    }

    public function aviso_clientes()
    {
        return $this->hasMany('App\Models\AvisoCliente');
    }

    public function getClientesAttribute()
    {
        $lista_clientes = '...';

        if($this->aviso_clientes){
            $lista_clientes = '';
            foreach($this->aviso_clientes as $aviso_cliente){
                $lista_clientes .= $aviso_cliente->cliente->nome . '   ';
            }
        }

        return $lista_clientes;
    }

    public function getClientesReduzidoAttribute()
    {
        $descricao_reduzida =  Str::limit($this->getClientesAttribute(), 20, '...');

        return $descricao_reduzida;
    }


    public function getDescricaoReduzidaAttribute()
    {
        $descricao_reduzida =  Str::limit($this->descricao, 20, '...');

        return $descricao_reduzida;
    }

    public function getTituloReduzidoAttribute()
    {
        $titulo_reduzido =  Str::limit($this->titulo, 20, '...');

        return $titulo_reduzido;
    }

    public function getUrgenteTextoAttribute()
    {
        $urgente = '';

        switch($this->urgente){
            case 'S' : {
                $urgente = 'Sim';
                break;
            }
            case 'N' : {
                $urgente = 'Não';
                break;
            }
            default : {
                $urgente = '---';
                break;
            }
        }

        return $urgente;
    }    

    public function getDataInicioOrdenacaoAttribute()
    {
        return ($this->dt_inicio) ? date('YmdHis', strtotime($this->dt_inicio)) : '';
    }
    
    public function getDataHoraInicioFormatadaAttribute()
    {
        return ($this->dt_inicio) ? date('d-m-Y H:i', strtotime($this->dt_inicio)) : '';
    }

    public function getDataInicioFormatadaAttribute()
    {
        return ($this->dt_inicio) ? date('d-m-Y', strtotime($this->dt_inicio)) : '';
    }        
    
    public function getDataInicioAjustadaAttribute()
    {
        return ($this->dt_inicio) ? date('Y-m-d', strtotime($this->dt_inicio)) : '';
    }

    public function getHoraInicioAjustadaAttribute()
    {
        return ($this->dt_inicio) ? date('H:i', strtotime($this->dt_inicio)): '';
    }    

    public function getDataHoraFimFormatadaAttribute()
    {
        return ($this->dt_fim) ? date('d-m-Y H:i', strtotime($this->dt_fim)) : '';
    }

    public function getDataFimFormatadaAttribute()
    {
        return ($this->dt_fim) ? date('d-m-Y', strtotime($this->dt_fim)) : '';
    }        
    
    public function getDataFimjustadaAttribute()
    {
        return ($this->dt_fim) ? date('Y-m-d', strtotime($this->dt_fim)) : '';
    }

    public function getHoraFimAjustadaAttribute()
    {
        return ($this->dt_fim) ? date('H:i', strtotime($this->dt_fim)): '';
    }    

}
