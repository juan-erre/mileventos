<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Entrada;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'entrada_id' => Entrada::factory(),
        ];
    }
}
