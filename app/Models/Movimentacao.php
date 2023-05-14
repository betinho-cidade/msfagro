<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Movimentacao extends Model
{

    public function efetivo()
    {
        return $this->belongsTo('App\Models\Efetivo');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function produtor()
    {
        return $this->belongsTo('App\Models\Produtor');
    }

    public function forma_pagamento()
    {
        return $this->belongsTo('App\Models\FormaPagamento');
    }

}
