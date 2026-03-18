<?php

namespace Database\Factories;

use App\Models\Entrada;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntradaFactory extends Factory
{
    protected $model = Entrada::class;

    public function definition()
    {
        return [
            'evento_id' => Evento::factory(),
        ];
    }
}