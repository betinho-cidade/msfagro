<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClienteUser extends Model
{
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente');
    }    

    public function perfil()
    {
        return $this->belongsTo('App\Models\Perfil');
    }    

}
