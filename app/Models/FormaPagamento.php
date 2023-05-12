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

    public function movimentacaos()
    {
        return $this->hasMany('App\Models\Movimentacao');
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
        $nome = $this->produtor ? $this->produtor->nome_produtor : '... / ...';

        return $nome;
    }

    public function getNomeProdutorFullAttribute()
    {
        $nome = $this->produtor ? $this->produtor->nome_produtor_full : '... / ...';

        return $nome;
    }

    public function getFormaAttribute()
    {
        $forma = (($this->produtor) ? Str::limit($this->produtor->nome, 20, '...') : 'Sem Produtor') . ' [' . $this->tipo_conta_texto . '] ';

        $forma = $forma . (($this->banco) ? ' [' . Str::limit($this->banco, 20, '...'). ']' : '');
        $forma = $forma . (($this->agencia) ? ' [age: ' . $this->agencia . ']' : '');
        $forma = $forma . (($this->conta) ? ' [cta: ' . $this->conta . ']' : '');
        $forma = $forma . (($this->pix) ? ' [pix: ' . $this->pix . ']' : '');

        return $forma;
    }

}
