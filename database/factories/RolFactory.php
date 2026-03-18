<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolFactory extends Factory
{
    protected $model = Rol::class;

    public function definition()
    {
        $roles = ['Administrador', 'Organizador', 'Registrado', 'Visitante'];

        return [
            'rol' => $this->faker->unique()->randomElement($roles),
        ];
    }
}
