<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Ubicacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition()
    {
        $fechaInicio = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $fechaFin = (clone $fechaInicio)->modify('+'.rand(1, 5).' days');

        return [
            'user_id' => User::factory(),
            'categoria_id' => Categoria::factory(),
            'ubicacion_id' => Ubicacion::factory(),
            'titulo' => $this->faker->sentence(3),
            'cartel' => null,
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'descripcion' => $this->faker->paragraph(),
            'num_entradas' => rand(10, 100),
            'reservas_habilitadas' => true,
        ];
    }
}