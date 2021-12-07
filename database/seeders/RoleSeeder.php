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




        Permission::create(['name'=>'admin',"description"=>"Permisos admin"])->syncRoles([$role1]);
        Permission::create(['name'=>'rh',"description"=>"Permisos RH"])->syncRoles([$role2]);
        Permission::create(['name'=>'sistemas',"description"=>"Permisos Sistemas"])->syncRoles([$role3]);
        Permission::create(['name'=>'superior',"description"=>"Permisos Superior"])->syncRoles([$role4]);

        Permission::create(['name'=>'admin.rh',"description"=>"Permisos Admin y RH"])->syncRoles([$role1,$role2]);
        Permission::create(['name'=>'admin.sistemas',"description"=>"Permisos Admin y Sistemas"])->syncRoles([$role1,$role3]);
        Permission::create(['name'=>'admin.superior',"description"=>"Permisos Admin y Superior"])->syncRoles([$role1,$role4]);

        Permission::create(['name'=>'rh.superior',"description"=>"RH y Superior"])->syncRoles([$role2,$role4]);

        Permission::create(['name'=>'admin.rh.sistemas',"description"=>"Permisos Admin, RH y Sistemas"])->syncRoles([$role1,$role2, $role3]);
        Permission::create(['name'=>'admin.rh.sistemas.superior',"description"=>"Permisos Admin, RH, Sistemas y Superior"])->syncRoles([$role1,$role2, $role3,$role4]);


    }
}
