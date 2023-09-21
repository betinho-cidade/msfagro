<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class AvisoCliente extends Model
{

    public function cliente(){

        return $this->belongsTo('App\Models\Cliente');
    }

    public function aviso(){

        return $this->belongsTo('App\Models\Aviso');
    }    

    public function getDescricaoReduzidaAttribute()
    {
        $descricao_reduzida =  Str::limit($this->descricao, 20, '...');

        return $descricao_reduzida;
    }

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome(), 20, '...');

        return $nome_reduzido;
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
        return ($this->dt_inicio && $this->hr_inicio) ? date('d-m-Y', strtotime($this->dt_inicio)) . ' '. date('H:i', strtotime($this->hr_inicio)) : '';
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
        return ($this->hr_inicio) ? date('H:i', strtotime($this->hr_inicio)): '';
    }    


    public function getDataHoraFimFormatadaAttribute()
    {
        return ($this->dt_fim && $this->hr_fim) ? date('d-m-Y', strtotime($this->dt_fim)) . ' '. date('H:i', strtotime($this->hr_fim)) : '';
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
        return ($this->hr_fim) ? date('H:i', strtotime($this->hr_fim)): '';
    }    

}
