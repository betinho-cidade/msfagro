<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Lancamento extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
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

    public function getTextoLancamentoAttribute(){

        if($this->tipo = 'VD'){
            $tipo_lancamento = 'Venda de: ';
        } else if($this->tipo = 'CP'){
            $tipo_lancamento = 'Compra de: ';
        }

        switch($this->item_macho){
            case 'M1' : {
                $texto_macho = ' Macho de 0 à 12 meses' . ' (qtd: ' . $this->qtd_macho . ')';
                break;
            }
            case 'M2' : {
                $texto_macho = ' Macho de 12 à 24 meses' . ' (qtd: ' . $this->qtd_macho . ')';
                break;
            }
            case 'M3' : {
                $texto_macho = ' Macho de 25 à 36 meses' . ' (qtd: ' . $this->qtd_macho . ')';
                break;
            }
            case 'M4' : {
                $texto_macho = ' Macho acima de 36 meses' . ' (qtd: ' . $this->qtd_macho . ')';
                break;
            }
            default : {
                $texto_macho = '';
                break;
            }
        }

        switch($this->item_femea){
            case 'F1' : {
                $texto_femea = ' Fêmea de 0 à 12 meses' . ' (qtd: ' . $this->qtd_femea . ')';
                break;
            }
            case 'F2' : {
                $texto_femea = ' Fêmea de 12 à 24 meses' . ' (qtd: ' . $this->qtd_femea . ')';
                break;
            }
            case 'F3' : {
                $texto_femea = ' Fêmea de 25 à 36 meses' . ' (qtd: ' . $this->qtd_femea . ')';
                break;
            }
            case 'F4' : {
                $texto_femea = ' Fêmea acima de 36 meses' . ' (qtd: ' . $this->qtd_femea . ')';
                break;
            }
            default : {
                $texto_femea = '';
                break;
            }
        }

        return $tipo_lancamento . $texto_macho . $texto_femea;

    }


}
