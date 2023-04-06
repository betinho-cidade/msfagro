<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class FormaPagamento extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function getTipoContaTextoAttribute()
    {
        $tipo_conta = '';

        switch($this->tipo_conta){
            case 'CC' : {
                $tipo_conta = 'Conta Corrente';
                break;
            }
            case 'CP' : {
                $tipo_conta = 'Conta Poupança';
                break;
            }
            case 'PX' : {
                $tipo_conta = 'Pix';
                break;
            }
            case 'BL' : {
                $tipo_conta = 'Boleto';
                break;
            }
            case 'ES' : {
                $tipo_conta = 'Espécie';
                break;
            }
            default : {
                $tipo_conta = '---';
                break;
            }
        }

        return $tipo_conta;
    }


}
