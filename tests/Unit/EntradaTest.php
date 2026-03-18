<?php

namespace Tests\Unit;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntradaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function una_entrada_pertenece_a_un_evento()
    {
        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();

        $this->assertEquals($evento->id, $entrada->evento->id);
    }

    /** @test */
    public function puede_tener_reservas()
    {
        $rol = Rol::factory()->create();
        $usuario = User::factory()->for($rol)->create();
        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();

        $reserva = Reserva::factory()->for($entrada)->for($usuario)->create();

        $this->assertNotNull($entrada->reserva);
        $this->assertEquals($usuario->id, $entrada->reserva->user->id);
    }
}