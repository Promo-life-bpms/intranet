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
        $role1 = Role::create(['name'=>'Admin']);
        $role2 = Role::create(['name'=>'RH']);
        $role3 = Role::create(['name'=>'Sistemas']);
        $role4 = Role::create(['name'=>'Superior']);
        $role5 = Role::create(['name'=>'Empleado']);


        Permission::create(['name'=>'communique.create',"description"=>"Administrar comunicados"])->syncRoles([$role1,$role2]);
        Permission::create(['name'=>'admin.users',"description"=>"Administrar usuarios"])->syncRoles([$role1]);
        Permission::create(['name'=>'admin.employees',"description"=>"Administrar empleados"])->syncRoles([$role1]);
        Permission::create(['name'=>'sistemas',"description"=>"Permisos Sistemas"])->syncRoles([$role3]);

    }
}
