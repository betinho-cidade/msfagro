<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Movimentacao extends Model
{

    public function lancamento()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

}
