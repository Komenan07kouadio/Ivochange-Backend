<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer des rôles
        $superAdminRole = Role::create(['name' => 'super admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'utilisateur']);

        // Créer des permissions
        $permissions = [
            'create utilisateurs',
            'edit utilisateurs',
            'delete utilisateurs',
            'view utilisateurs',
            'manage articles',
            'publish articles',
            'edit own profile',
            'view own profile'
        ];

        // Créer et assigner des permissions à chaque rôle
        foreach ($permissions as $permission) {
            $perm = Permission::create(['name' => $permission]);

            // Le super admin a toutes les permissions
            $superAdminRole->givePermissionTo($perm);
        }

        // L'admin a des permissions spécifiques
        $adminRole->givePermissionTo([
            'create utilisateurs',
            'edit utilisateurs',
            'view utilisateurs',
            'manage articles',
            'publish articles',
        ]);

        // L'utilisateur a des permissions limitées
        $userRole->givePermissionTo([
            'edit own profile',
            'view own profile',
        ]);
    }
}
