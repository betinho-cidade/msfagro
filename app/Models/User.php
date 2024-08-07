<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getAvatarAttribute()
    {
        return ($this->path_avatar) ? asset('images/avatar').'/'.$this->path_avatar : asset('images/avatar') .'/avatar.png';
    }


    public function getDataAlteracaoAttribute()
    {
        return date('d/m/Y H:i:s', strtotime($this->updated_at));
    }


    public function getPerfilIdAttribute()
    {
        $perfil = $this->rolesAll();

        return $perfil->first()->id;
    }


    public function getPerfilAttribute()
    {
        $perfil = $this->rolesAll();

        return $perfil->first()->name;
    }


    public function getSituacaoAttribute()
    {
        $situacao = $this->rolesAll()
            ->withPivot('status');

        return $situacao->first()->pivot;
    }


    public function rolesAll()
    {
        return $this->belongsToMany('App\Models\Role')
                    ->withTimestamps();
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')
            ->withPivot('status')
            ->wherePivot('status', 'A')
            ->withTimestamps();
    }

    public function cliente_user(){
        return $this->hasOne('App\Models\ClienteUser');
    }            

    // public function hasPermission_role(Permission $permission)
    // {
    //     return $this->hasAnyRoles($permission->roles);
    // }

    public function hasPermission(Permission $permission)
    {
        if($this->perfil == 'Gestor'){
            return $this->hasAnyRoles($permission->roles);
        } else{
            return $this->hasAnyPerfils($permission->perfils);
        }
    }    

    public function hasAnyRoles($roles)
    {
        if (is_array($roles) || is_object($roles)) {

            $return = false;
            foreach ($roles as $role) {

                if ($this->roles->contains('name', $role->name)) {
                    $return = true;
                    continue;
                }
            }
            return $return;
        }

        return $this->roles->contains('name', $roles);
    }

    public function hasAnyPerfils($perfils)
    {
        if (is_array($perfils) || is_object($perfils)) {

            $return = false;
            foreach ($perfils as $perfil) {

                if ($this->cliente_user->perfil->name == $perfil->name) {
                    $return = true;
                    continue;
                }
            }
            return $return;
        }

        return $this->cliente_user->perfil->name == $perfils->name;
    }    

}
