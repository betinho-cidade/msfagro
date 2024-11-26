<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;


class Lucro extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function produtor()
    {
        return $this->belongsTo('App\Models\Produtor');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo('App\Models\FormaPagamento');
    }

    public function getObservacaoReduzidaAttribute()
    {
        $observacao_reduzida =  Str::limit($this->observacao, 50, '...');

        return $observacao_reduzida;
    }

    public function getDataLancamentoOrdenacaoAttribute()
    {
        return ($this->data_lancamento) ? date('YmdHis', strtotime($this->data_lancamento)) : '';
    }

    public function getDataLancamentoFormatadaAttribute()
    {
        return date('d/m/Y', strtotime($this->data_lancamento));
    }

    public function getDataLancamentoAjustadaAttribute()
    {
        return ($this->data_lancamento) ? date('Y-m-d', strtotime($this->data_lancamento)) : '';
    }

    public function getLinkComprovanteAttribute()
    {
        return ($this->path_comprovante) ? route('lucro.download', ['lucro' => $this->id]) : '';
    }

    public function getLinkComprovanteGuestAttribute()
    {
        if($this->path_comprovante){

            $link = route('download_lucro', ['comprovante' => Crypt::encryptString($this->id)]);

            return $link;

        } else {
            return ' ';
        }
    }
}

