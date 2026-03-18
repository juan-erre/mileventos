<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        // Inserta los roles a registrar por la aplicación
        $roles = ['Administrador', 'Organizador', 'Registrado'];

        foreach ($roles as $rol) {
            Rol::firstOrCreate(['rol' => $rol]);
        }
    }
}
