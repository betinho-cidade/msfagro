<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Movimentacao extends Model
{

    public function efetivo()
    {
        return $this->belongsTo('App\Models\Efetivo');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function produtor()
    {
        return $this->belongsTo('App\Models\Produtor');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo('App\Models\FormaPagamento');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function getSegmentoTextoAttribute()
    {
        $segmento = '';

        switch($this->segmento){
            case 'MF' : {
                $segmento = 'Mov.Fiscal';
                break;
            }
            case 'MG' : {
                $segmento = 'Mov.Bovina';
                break;
            }
            default : {
                $segmento = '---';
                break;
            }
        }

        return $segmento;
    }



    public function getTipoMovimentacaoTextoAttribute()
    {
        $tipo_movimentacao = '';

        switch($this->tipo){
            case 'R' : {
                $tipo_movimentacao = 'Receita';
                break;
            }
            case 'D' : {
                $tipo_movimentacao = 'Despesa';
                break;
            }
            default : {
                $tipo_movimentacao = '---';
                break;
            }
        }

        return $tipo_movimentacao;
    }

    public function getItemTextoReduzidoAttribute()
    {
        $item_texto_reduzido =  Str::limit($this->item_texto, 15, '...');

        return $item_texto_reduzido;
    }

    public function getDataProgramadaOrdenacaoAttribute()
    {
        return ($this->data_programada) ? date('YmdHis', strtotime($this->data_programada)) : '';
    }

    public function getDataProgramadaFormatadaAttribute()
    {
        return date('d/m/Y', strtotime($this->data_programada));
    }

    public function getDataProgramadaAjustadaAttribute()
    {
        return ($this->data_programada) ? date('Y-m-d', strtotime($this->data_programada)) : '';
    }

    public function getDataPagamentoFormatadaAttribute()
    {
        return ($this->data_pagamento) ? date('d/m/Y', strtotime($this->data_pagamento)) : '...';
    }

    public function getDataPagamentoAjustadaAttribute()
    {
        return ($this->data_pagamento) ? date('Y-m-d', strtotime($this->data_pagamento)) : '';
    }

}
