<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];


    public function boot()
    {
        $this->registerPolicies();

        try {

            $permission_roles = \App\Models\Permission::with('roles')->get();

            foreach($permission_roles as $permission){
                Gate::define($permission->name, function(User $user) use ($permission) {
                    return $user->hasPermission($permission);
                });
            }
           
        } catch (\Exception $e) {
            return [];
        }


    }

}
