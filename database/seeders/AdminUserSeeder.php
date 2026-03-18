<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        // Inserta el usuario administrador global de la aplicación
        User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'rol_id' => 1, 
                'name' => 'Administrador',
                'password' => bcrypt('admin_mileventos'),
                'email_verified_at' => now(),
                'foto' => 'default/perfil.png'
            ]
        ); 
    }
}
