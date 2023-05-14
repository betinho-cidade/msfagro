<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;


class Fazenda extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function origems(){

        return $this->hasMany('App\Models\Efetivo');
    }

    public function destinos(){

        return $this->hasMany('App\Models\Efetivo');
    }


    public function getEnderecoAttribute()
    {
        $endereco = '...';

        if($this->end_cidade){
            $endereco = $this->end_cidade;
            if($this->end_uf) {
                $endereco = $endereco . '/' . $this->end_uf;
            }
        } else {
            if($this->end_uf){
                $endereco = $this->end_uf;
            }
        }

        return $endereco;
    }

    public function getNomeReduzidoAttribute()
    {
        $nome_reduzido =  Str::limit($this->nome, 150, '...');

        return $nome_reduzido;
    }

    public function getNomeFazendaAttribute()
    {
        $nome_fazenda = $this->nome . ' - ' . $this->endereco;

        return $nome_fazenda;
    }

    public function getNomeFazendaReduzidoAttribute()
    {
        $nome_fazenda_reduzido =  Str::limit($this->getNomeFazendaAttribute(), 150, '...');

        return $nome_fazenda_reduzido;
    }


}
