<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Recursos Humanos']);
        $role3 = Role::create(['name' => 'Sistemas']);
        $role5 = Role::create(['name' => 'Empleado']);

        Permission::create(['name' => 'admin', "description" => "Permisos Admin"])->syncRoles([$role1]);
        Permission::create(['name' => 'rh', "description" => "Permisos RH"])->syncRoles([$role2]);
        Permission::create(['name' => 'sistemas', "description" => "Permisos Sistemas"])->syncRoles([$role3]);
    }
}
