<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\User;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {    
        
        // Crear usuarios de prueba y guardar las instancias
        $maria = User::firstOrCreate(
            ['email' => 'maria@email.com'],
            [
                'rol_id' => 2, 
                'name' => 'María Sanz Luque',
                'password' => bcrypt('maria00'),
                'email_verified_at' => now(),
                'foto' => 'pruebas/mujer.png'
            ]
        );   

        User::firstOrCreate(
            ['email' => 'jose@email.com'],
            [
                'rol_id' => 3, 
                'name' => 'José López Fernández',
                'password' => bcrypt('jose00'),
                'email_verified_at' => now(),
                'foto' => 'pruebas/hombre.png'
            ]
        );

        // Array de eventos de prueba, para el usuario María que tiene el rol de organizador
        $eventos = [
            [
                'user_id' => 1,
                'categoria_id' => 3,
                'ubicacion_id' => 15,
                'titulo' => 'FERIA DE CÓRDOBA',
                'cartel' => 'pruebas/fiesta.jpg',
                'fecha_inicio' => '2026-05-20',
                'fecha_fin' => '2026-05-30',
                'descripcion' => 'Este año, la feria de Córdoba será unica...',
                'num_entradas' => 0,
                'reservas_habilitadas' => true
            ],
            [
                'user_id' => $maria->id,
                'categoria_id' => 5,
                'ubicacion_id' => 26,
                'titulo' => 'EXPOSICIÓN FOTOGRÁFICA',
                'cartel' => 'pruebas/exposicion.jpg',
                'fecha_inicio' => '2026-09-27',
                'fecha_fin' => '2026-10-15',
                'descripcion' => 'Nueva exposición de fotografía en el museo... ',
                'num_entradas' => 50,
                'reservas_habilitadas' => true
                
            ],
            [
                'user_id' => $maria->id,
                'categoria_id' => 2,
                'ubicacion_id' => 18,
                'titulo' => 'TEMPORADA DE ESQUÍ 2026',
                'cartel' => 'pruebas/deportiva.jpg',
                'fecha_inicio' => '2026-12-15',
                'fecha_fin' => '2027-02-28',
                'descripcion' => 'Temporada 2026 en Sierra nevada... ',
                'num_entradas' => 0,
                'reservas_habilitadas' => true
            ],
            [
                'user_id' => $maria->id,
                'categoria_id' => 1,
                'ubicacion_id' => 4,
                'titulo' => 'CUENTA CUENTOS',
                'cartel' => 'pruebas/cultural.webp',
                'fecha_inicio' => '2026-10-21',
                'fecha_fin' => '2026-10-21',
                'descripcion' => 'Cuentacuentos en la biblioteca provincial de Almería...',
                'num_entradas' => 100,
                'reservas_habilitadas' => true
            ],
            [
                'user_id' => $maria->id,
                'categoria_id' => 7,
                'ubicacion_id' => 31,
                'titulo' => 'CONGRESO DE CIRUJANOS 2026',
                'cartel' => 'pruebas/congreso.jpg',
                'fecha_inicio' => '2026-01-08',
                'fecha_fin' => '2026-01-12',
                'descripcion' => 'Madrid acogerá una vez más el congreso...',
                'num_entradas' => 0,
                'reservas_habilitadas' => true
            ],
        ];

        // Crear los eventos
        foreach ($eventos as $eventoData) {
            $evento = Evento::firstOrCreate(
                ['titulo' => $eventoData['titulo']],
                $eventoData
            );

            // Crear entradas automáticas
            for ($i = 1; $i <= $eventoData['num_entradas']; $i++) {
                $evento->entradas()->create();
            }
        }
    }
}
