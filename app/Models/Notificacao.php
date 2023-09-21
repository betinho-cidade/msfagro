<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class Notificacao extends Model
{
    use HasFactory;

    public function movimentacao(){

        return $this->belongsTo('App\Models\Movimentacao');
    }

    public function cliente_notificacaos()
    {
        return $this->hasMany('App\Models\ClienteNotificacao');
    }    

    public function getDataInicioFormatadaAttribute()
    {
        return ($this->data_inicio) ? date('d-m-Y H:i', strtotime($this->data_inicio)) : '';
    }

    public function getDataInicioAjustadaAttribute()
    {
        return ($this->data_inicio) ? date('Y-m-d', strtotime($this->data_inicio)): '';
    }

    public function getHoraInicioAjustadaAttribute()
    {
        return ($this->data_inicio) ? date('H:i', strtotime($this->data_inicio)): '';
    }

    public function getDataFimFormatadaAttribute()
    {
        return ($this->data_fim) ? date('d-m-Y H:i', strtotime($this->data_fim)) : '';
    }

    public function getDataFimAjustadaAttribute()
    {
        return ($this->data_fim) ? date('Y-m-d', strtotime($this->data_fim)): '';
    }

    public function getDataFimReduzidaAttribute()
    {
        return ($this->data_fim) ? date('d/m/Y', strtotime($this->data_fim)) : '';
    }       

    public function getHoraFimAjustadaAttribute()
    {
        return ($this->data_fim) ? date('H:i', strtotime($this->data_fim)): '';
    }    

    public function getTerminaEmAttribute()
    {
        return ($this->data_fim) ? date('H:i d/m', strtotime($this->data_fim)) : '';
    }    

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 35, '...');

        return $nome_reduzido;
    }

    public function getResumoReduzidoAttribute()
    {
        $resumo_reduzido =  Str::limit($this->resumo, 150, '...');

        return $resumo_reduzido;
    } 
    
    public function getTempoInicioAttribute()
    {
        $data_inicio = new Carbon($this->data_inicio);

        $tempo_inicio = $data_inicio->locale('pt-BR')->diffForHumans(now(), CarbonInterface::DIFF_ABSOLUTE);

        return $tempo_inicio;
    }    
    
    public function getAoVivoAttribute()
    {
        $data_inicio = new Carbon($this->data_inicio);
        $data_fim = new Carbon($this->data_fim);
        $data_atual = Carbon::now();

        if($data_atual >= $data_inicio && $data_atual <= $data_fim){
            return true;
        } else {
            return false;
        }
    }            
}
