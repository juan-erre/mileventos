<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        // Inserta las categorias a utilizar por la aplicación
        $categorias = [
            'Cultural',
            'Deportivo',
            'Feria',
            'Concierto',
            'Exposición',
            'Gastronómico',
            'Congreso',
            'Político',
            'Academico',
            'Otros'
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(['categoria' => $categoria]);
        }
    }
}
