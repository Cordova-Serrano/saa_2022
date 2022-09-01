<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder 
{
    public function run() 
    {
        $super = Role::where('name', 'super')->first();
        $admin = Role::where('name', 'admin')->first();
        $user = Role::where('name', 'user')->first();

        $user = new User([
            'name'      => 'SAA Superusuario', 
            'email'     => 'super@test.com', 
            'username'  => 'SAA-Super', 
            'password'  => bcrypt('super1234')
        ]);
        $user->save();
        $user->roles()->attach($super);

        $user = new User([
            'name'      => 'SAA Administrador', 
            'email'     => 'admin@test.com', 
            'username'  => 'SAA-Admin', 
            'password'  => bcrypt('admin1234')
        ]);
        $user->save();
        $user->roles()->attach($admin);

        $user = new User([
            'name'      => 'SAA User', 
            'email'     => 'user@test.com', 
            'username'  => 'SAA-User', 
            'password'  => bcrypt('user1234')
        ]);
        $user->save();
        $user->roles()->attach($user);
    }
}