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

    public function lucros(){

        return $this->hasMany('App\Models\Lucro');
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
            case 'CT' : {
                $tipo_conta = 'Cessão de Crédito';
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

        $forma = (($this->produtor) ? Str::of($this->produtor->nome)->explode(' ')[0] : '') . ' [' . $this->tipo_conta_texto . '] ';

        $banco = (($this->banco) ? Str::limit($this->banco, 20, '...') : '');
        $agencia = (($this->agencia) ? $this->agencia : '');
        $conta = (($this->conta) ? $this->conta : '');

        if($banco && $agencia && $conta){
            $forma = $forma . '[' . $banco . ': ' . $agencia . '/' . $conta . ']';
        } else if($banco && $agencia && !$conta){
            $forma = $forma . '[' . $banco . ': ' . $agencia . ']';
        } else if($banco && !$agencia && $conta){
            $forma = $forma . '[' . $banco . ': ' . $conta . ']';
        } else if(!$banco && $agencia && $conta){
            $forma = $forma . '[' . $agencia . '/' . $conta . ']';
        } else if($banco && !$agencia && !$conta){
            $forma = $forma . '[' . $banco . ']';
        } else if(!$banco && $agencia && !$conta){
            $forma = $forma . '[ag: ' . $agencia . ']';
        } else if(!$banco && !$agencia && $conta){
            $forma = $forma . '[conta: ' . $conta . ']';
        }          

        $forma = $forma . (($this->pix) ? ' [pix: ' . $this->pix . ']' : '');

        return $forma;
    }

}
