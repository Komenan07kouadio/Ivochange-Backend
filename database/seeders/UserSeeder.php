<?php

namespace Database\Seeders;

use App\Models\Utilisateurs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Utisateurs;
use Illuminate\Foundation\Auth\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'un utilisateur via la factory
        $first_User =  Utilisateurs::factory()->create();

        // Assignation d'un rôle à l'utilisateur
        $first_User->assignRole(Role::find(1)->name); 
        // Assignation du rôle via l'ID ou tu peux utiliser le nom directement.

         // Création d'un utilisateur via la factory
         $second_User =  Utilisateurs::factory()->create();

         // Assignation d'un rôle à l'utilisateur
         $first_User->assignRole(Role::find(2)->name);

         $permissions =  $second_User->getPermissionsViaRoles();
         echo $permissions;
    }
}
