<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteNotificacao extends Model
{
    use HasFactory;

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

    public function notificacao()
    {
        return $this->belongsTo('App\Models\Notificacao');
    }

}
