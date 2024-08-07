<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleUserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PermissionRoleSeeder::class);
        $this->call(PerfilSeeder::class);
        $this->call(PerfilPermissionSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(GooglemapSeeder::class);
    }
}
