<?php

namespace Database\Seeders;


use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name'          => 'super',
            'description'   => 'Superusuario'
        ]);

        Role::create([
            'name'          => 'admin',
            'description'   => 'Administración'
        ]);

        Role::create([
            'name'          => 'user',
            'description'   => 'Usuario'
        ]);
    }
}
