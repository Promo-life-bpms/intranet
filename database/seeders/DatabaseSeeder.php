<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
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
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrador', // optional
            'description' => '', // optional
        ]);

        $systems = Role::create([
            'name' => 'systems',
            'display_name' => 'Sistemas', // optional
            'description' => '', // optional
        ]);

        $rh = Role::create([
            'name' => 'rh',
            'display_name' => 'Recursos Humanos', // optional
            'description' => '', // optional
        ]);

        $empleado = Role::create([
            'name' => 'employee',
            'display_name' => 'Empleado', // optional
            'description' => '', // optional
        ]);

        $manager = Role::create([
            'name' => 'manager',
            'display_name' => 'Manager', // optional
            'description' => '', // optional
        ]);

        User::create([
            'name' => 'Antonio',
            'lastname' => 'Tomas',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => '',
        ])->attachRole($admin);

        User::create([
            'name' => 'Jacobo ',
            'lastname' => 'Levy Hano',
            'email' => 'jacobo@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        User::create([
            'name' => 'Daniel ',
            'lastname' => 'Levy Hano',
            'email' => 'daniel@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);


        User::create([
            'name' => 'David',
            'lastname' => 'Levy Hano',
            'email' => 'david@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);
        
        User::create([
            'name' => 'Raul',
            'lastname' => 'Torres Marquez',
            'email' => 'raul.torres@promolife.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        //Asistente Direccion
        User::create([
            'name' => 'Ana Victoria',
            'lastname' => 'Sosa Juarez',
            'email' => 'victoria@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($empleado);

        User::create([
            'name' => 'Rosa Elba',
            'lastname' => 'Montesinos Licona',
            'email' => 'administracionventas@promolife.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($empleado);

        //Recursos Humanos
        User::create([
            'name' => 'Denisse Adriana',
            'lastname' => 'Murillo Mayen',
            'email' => 'denisse@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($rh);

        User::create([
            'name' => 'Ana Maria',
            'lastname' => 'Jiménez Martínez',
            'email' => 'maria@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($rh);

        User::create([
            'name' => 'Ana Miriam',
            'lastname' => 'Pérez Maya',
            'email' => 'miriam@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($rh);
        
        //Sistemas
        User::create([
            'name' => 'Ricardo Eusebio',
            'lastname' => 'Morales Negrin',
            'email' => 'sistemas@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($systems);
        
        //Administracion
        User::create([
            'name' => 'Monica',
            'lastname' => 'Reyes Resendiz',
            'email' => 'monica@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        User::create([
            'name' => 'Guillermo David',
            'lastname' => 'Ramírez Romero',
            'email' => 'tesoreria@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($empleado);

        User::create([
            'name' => 'Shanat Mirelle',
            'lastname' => 'Aquino Carachure',
            'email' => 'facturas@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($empleado);


        //Ventas VH
        User::create([
            'name' => 'Ricardo Alberto',
            'lastname' => 'Zamora Rodríguez',
            'email' => 'ricardo@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        //Importaciones
        User::create([
            'name' => 'Jesica',
            'lastname' => 'González Alvarez',
            'email' => 'jesica@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        //Diseno

        //Operaciones
        User::create([
            'name' => 'Carlos Lenin',
            'lastname' => 'Reyes Ramos',
            'email' => 'lenin@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        //Ventas PL
        User::create([
            'name' => 'Jaime',
            'lastname' => 'González Rueda',
            'email' => 'jaime.gonzalez@promolife.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);
       
        //Tecnologia e Innovacion
        User::create([
            'name' => 'Federico',
            'lastname' => 'Solano Reyes',
            'email' => 'federico@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

           //Cancun
           User::create([
            'name' => 'Abel',
            'lastname' => 'Ramírez Cortes',
            'email' => 'abel@trademarket.com.mx',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
            'remember_token' => '',
        ])->attachRole($manager);

        /* Empresas */

        Company::create([
            'name_company' => 'Promolife',
            'description_company' => 'San Andrés Atoto No. 155 Piso 1 Local B, 53550 Naucalpan de Juárez, México'
        ]);

        Company::create([
            'name_company' => 'BH Trademarket',
            'description_company' => 'San Andrés Atoto No. 155 Piso 1 Local B, 53550 Naucalpan de Juárez, México'
        ]);

        Company::create([
            'name_company' => 'Promodreams',
            'description_company' => 'San Andrés Atoto No. 155 Piso 1 Local B, 53550 Naucalpan de Juárez, México'
        ]);

        Company::create([
            'name_company' => 'Trademarket',
            'description_company' => 'San Andrés Atoto No. 155 Piso 1 Local B, 53550 Naucalpan de Juárez, México'
        ]);

        /* Departamentos */
        Department::Create([
            'id' => 1,
            'name' => 'Recursos Humanos'
        ]);

        Department::Create([
            'id' => 2,
            'name' => 'Administracion'
        ]);

        Department::Create([
            'id' => 3,
            'name' => 'Ventas BH'
        ]);

        Department::Create([
            'id' => 4,
            'name' => 'Importaciones'
        ]);

        Department::Create([
            'id' => 5,
            'name' => 'Diseno'
        ]);

        Department::Create([
            'id' => 6,
            'name' => 'Sistemas'
        ]);

        Department::Create([
            'id' => 7,
            'name' => 'Operaciones'
        ]);

        Department::Create([
            'id' => 8,
            'name' => 'Ventas PL'
        ]);

        Department::Create([
            'id' => 9,
            'name' => 'Tecnologia e Innovacion'
        ]);

        Department::Create([
            'id' => 10,
            'name' => 'E-commerce'
        ]);

        Department::Create([
            'id' => 11,
            'name' => 'Cancun'
        ]);


        /* Puestos */

        Position::create([
            'name' => 'Director BH Recursos Humanos',
            'department_id' => 1
        ]);

        Position::create([
            'name' => 'Director PL Recursos Humanos',
            'department_id' => 1
        ]);

        Position::create([
            'name' => 'Gerente Recursos Humanos',
            'department_id' => 1
        ]);

        Position::create([
            'name' => 'Recepcion',
            'department_id' => 1
        ]);


        Position::create([
            'name' => 'Asistente de Recursos Humanos',
            'department_id' => 1
        ]);


        Position::create([
            'name' => 'Limpieza',
            'department_id' => 1
        ]);


        Position::create([
            'name' => 'Director BH Administracion',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Director PL Administracion',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Gerente Administrativo',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Tesoreria',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Facturacion',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Asistente Factural PL',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Asistente CXC BH',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'CXC BH',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'CXC PL',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'CXC PL',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Asistente DUAL',
            'department_id' => 2
        ]);

        Position::create([
            'name' => 'Director BH Ventas',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Asistente de Direccion',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Gerente Comercial Ventas BH',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Ejecutivo de Ventas BH',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Asistente de Ventas BH',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Ejecutivo de Ventas EUA',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Asistente de Dirección Comercial BH',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Asistente de Ventas PL',
            'department_id' => 3
        ]);

        Position::create([
            'name' => 'Director BH Importaciones',
            'department_id' => 4
        ]);

        Position::create([
            'name' => 'Gerente de importaciones',
            'department_id' => 4
        ]);

        Position::create([
            'name' => 'Ejecutivo de importaciones',
            'department_id' => 4
        ]);

        Position::create([
            'name' => 'Asistente de importaciones',
            'department_id' => 4
        ]);

        Position::create([
            'name' => 'Importaciones DUAL',
            'department_id' => 4
        ]);

        Position::create([
            'name' => 'Director General PL Diseno',
            'department_id' => 5
        ]);

        Position::create([
            'name' => 'Diseñador gráfico',
            'department_id' => 5
        ]);

        Position::create([
            'name' => 'Residencias diseño',
            'department_id' => 5
        ]);

        Position::create([
            'name' => 'Director General PL Sistemas',
            'department_id' => 6
        ]);

        Position::create([
            'name' => 'Sistemas',
            'department_id' => 6
        ]);

        Position::create([
            'name' => 'Director General BH Operaciones',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Director General PL',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Gerente de compras, mesa de control y calidad',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Asistente de operaciones',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Auxiliar de calidad',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Mesa de control PL',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Mesa de control BH',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Compras estratégicas nacionales',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Compras',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Residencias Compras',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Jefe de tráfico',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Jefe de tráfico',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Ayudante de Almacen',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Mensajería',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Jefe de Almacén y Empaque',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Asistente de Almacen',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Jefe de Logística',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Operador de grabado láser',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Chofer',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Mensajero/Chofer/Almacenista',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Chofer/Almacenista',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Mensajería',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Paquetería e inventario de Stock',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Auxiliar de calidad',
            'department_id' => 7
        ]);

        Position::create([
            'name' => 'Director General PL Ventas',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Asistente de Dirección PL',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Gerente Comercial Ventas PL',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Director General PL Ventas',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Asisente de Gerente Comercial Ventas PL',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Subgerente Comercial',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Ejecutivo de ventas',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Asistente de Ventas PL',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Prospección',
            'department_id' => 8
        ]);

        Position::create([
            'name' => 'Director General PL Tecnologia e Innovacion',
            'department_id' => 9
        ]);

        Position::create([
            'name' => 'Project Manager',
            'department_id' => 9
        ]);

        Position::create([
            'name' => 'Desarrollador DUAL',
            'department_id' => 9
        ]);

        Position::create([
            'name' => 'Director General PL E-Commerce',
            'department_id' => 10
        ]);

        Position::create([
            'name' => 'Director General BH E-Commerce',
            'department_id' => 10
        ]);

        Position::create([
            'name' => 'Project Manager',
            'department_id' => 10
        ]);

        Position::create([
            'name' => 'E-Commerce',
            'department_id' => 10
        ]);

        Position::create([
            'name' => 'Director BH Cancun',
            'department_id' => 11
        ]);

        Position::create([
            'name' => 'Gerente de Ventas Cancun',
            'department_id' => 11
        ]);

        Position::create([
            'name' => 'Administración Ventas Cancun',
            'department_id' => 11
        ]);

        Position::create([
            'name' => 'Ejecutivo de Ventas Cancun',
            'department_id' => 11
        ]);

        Position::create([
            'name' => 'Mensajero',
            'department_id' => 11
        ]);
    }
}
