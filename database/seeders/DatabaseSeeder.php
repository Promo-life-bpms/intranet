<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
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

        $this->call(RoleSeeder::class);

        User::create([
            'name' => 'Antonio',
            'lastname' => 'Tomas',
            'email' => 'admin@admin',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => '',
        ])->assignRole('Admin');

        User::create([
            'name' => '0scar',
            'lastname' => 'Chavez',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi', //123456789
            'remember_token' => '',
        ])->assignRole('Admin');

        User::create([
            'name' => 'Diego',
            'lastname' => 'Navarrete',
            'email' => 'empleado@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi',
            'remember_token' => '',
        ])->assignRole('RH');

        User::create([
            'name' => 'Jose',
            'lastname' => 'Navarrete',
            'email' => 'jose@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi',
            'remember_token' => '',
        ])->assignRole('Sistemas');

        User::create([
            'name' => 'Bradon Iriarte',
            'lastname' => 'Iriarte',
            'email' => 'brandon@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi',
            'remember_token' => '',
        ])->assignRole('Superior');

        User::create([
            'name' => 'Andres',
            'lastname' => 'Lopez',
            'email' => 'andres@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Mauricio',
            'lastname' => 'Sosa',
            'email' => 'mau@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Ricardo',
            'lastname' => 'Zuniga',
            'email' => 'ricado@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Diego',
            'lastname' => 'Lozano',
            'email' => 'diegol@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'David',
            'lastname' => 'Huerta',
            'email' => 'davidh@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Samuel',
            'lastname' => 'Hernandez',
            'email' => 'samuel@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Horacio',
            'lastname' => 'Martinez',
            'email' => 'horacio@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Humberto ',
            'lastname' => 'Roman',
            'email' => 'humberto@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Benda',
            'lastname' => 'Matinez',
            'email' => 'brenda@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Pablo',
            'lastname' => 'Saucedo',
            'email' => 'pablo@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

        User::create([
            'name' => 'Ana',
            'lastname' => 'Lopez',
            'email' => 'ana@test.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => '',
        ])->assignRole('Empleado');

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
