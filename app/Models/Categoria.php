<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

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


}
