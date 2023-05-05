<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Fazenda extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
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

    public function getNomeFazendaAttribute()
    {
        return $this->nome . ' - ' . $this->endereco;
    }

}
