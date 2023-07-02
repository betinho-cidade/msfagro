<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteGooglemap extends Model
{

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }

}
