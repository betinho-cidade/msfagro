<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class Efetivo extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Models\Empresa');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function origem()
    {
        return $this->belongsTo('App\Models\Fazenda');
    }

    public function destino()
    {
        return $this->belongsTo('App\Models\Fazenda');
    }

    public function movimentacao(){

        return $this->hasOne('App\Models\Movimentacao');
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

    public function getTipoEfetivoTextoAttribute()
    {
        $tipo_efetivo = '';

        switch($this->tipo){
            case 'CP' : {
                $tipo_efetivo = 'Compra';
                break;
            }
            case 'VD' : {
                $tipo_efetivo = 'Venda';
                break;
            }
            case 'EG' : {
                $tipo_efetivo = 'Engorda';
                break;
            }
            default : {
                $tipo_efetivo = '---';
                break;
            }
        }

        return $tipo_efetivo;
    }


    public function getClassificacaoMachoAttribute(){

        $classificacao = '...';

        switch($this->item_macho){
            case 'M1' : {
                $classificacao = 'Macho de 0 à 12 meses';
                break;
            }
            case 'M2' : {
                $classificacao = 'Macho de 12 à 24 meses';
                break;
            }
            case 'M3' : {
                $classificacao = 'Macho de 25 à 36 meses';
                break;
            }
            case 'M4' : {
                $classificacao = 'Macho acima de 36 meses';
                break;
            }
            default : {
                $classificacao = '...';
                break;
            }
        }

        return $classificacao;
    }


    public function getClassificacaoFemeaAttribute(){

        $classificacao = '...';

        switch($this->item_femea){
            case 'F1' : {
                $classificacao = 'Fêmea de 0 à 12 meses';
                break;
            }
            case 'F2' : {
                $classificacao = 'Fêmea de 12 à 24 meses';
                break;
            }
            case 'F3' : {
                $classificacao = 'Fêmea de 25 à 36 meses';
                break;
            }
            case 'F4' : {
                $classificacao = 'Fêmea acima de 36 meses';
                break;
            }
            default : {
                $classificacao = '...';
                break;
            }
        }

        return $classificacao;
    }


    public function getTextoEfetivoAttribute(){

        if($this->tipo == 'VD'){
            $tipo_efetivo = 'Venda de: ';
        } else if($this->tipo == 'CP'){
            $tipo_efetivo = 'Compra de: ';
        }

        $texto_macho = ($this->qtd_macho > 0) ? $this->getClassificacaoMachoAttribute() . ' (qtd: ' . $this->qtd_macho . ')' : '';

        $texto_femea = ($this->qtd_femea > 0) ? $this->getClassificacaoFemeaAttribute() . ' (qtd: ' . $this->qtd_femea . ')' : '';

        return $tipo_efetivo . $texto_macho . $texto_femea;
    }


    public function getTotalBovinosAttribute()
    {
        return number_format($this->qtd_macho + $this->qtd_femea, 0, ',', '.');
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


    public function getMesReferenciaListagemAttribute (){

        $mes_referencia =  Carbon::parse($this->data_programada); 

        return Str::padLeft($mes_referencia->month, 2, '0') . '-' . $mes_referencia->year;

    }


}
