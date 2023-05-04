<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Empresa extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
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

    public function getNomeEmpresaAttribute()
    {
        $nome = $this->nome . ' - ' . $this->tipo_pessoa. ':' . $this->cpf_cnpj;

        return $nome;
    }


}
