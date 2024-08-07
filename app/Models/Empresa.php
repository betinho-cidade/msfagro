<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Empresa extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function efetivos()
    {
        return $this->hasMany('App\Models\Efetivo');
    }

    public function movimentacaos()
    {
        return $this->hasMany('App\Models\Movimentacao');
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

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 15, '...');

        return $nome_reduzido;
    }

    public function getNomeEmpresaAttribute()
    {
        $nome_empresa = $this->nome . ' - ' . $this->tipo_pessoa. ':' . $this->cpf_cnpj;

        return $nome_empresa;
    }

    public function getNomeEmpresaReduzidoAttribute()
    {
        $nome_empresa_reduzido =  Str::limit($this->getNomeEmpresaAttribute(), 15, '...');

        return $nome_empresa_reduzido;
    }

    public function getHasLancamentoAttribute(){

        return ($this->movimentacaos()->count() > 0 || $this->efetivos()->count() > 0) ? true : false;
    }


}
