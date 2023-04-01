<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Cliente extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
