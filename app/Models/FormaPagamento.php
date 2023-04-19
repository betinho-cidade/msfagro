<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;


class FormaPagamento extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function produtor()
    {
        return $this->belongsTo('App\Models\Produtor');
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
            case 'NT' : {
                $tipo_conta = 'Numerário em Trânsito';
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

    public function getNomeProdutorAttribute()
    {
        $nome = $this->produtor ? ($this->produtor->nome . ' / ' . $this->produtor->cpf_cnpj) : '... / ...';
        $nome = Str::limit($nome, 100, '...');

        return $nome;
    }

    public function getNomeProdutorFullAttribute()
    {
        $nome = $this->produtor ? ($this->produtor->nome . ' / ' . $this->produtor->cpf_cnpj) : '... / ...';

        return $nome;
    }

}
