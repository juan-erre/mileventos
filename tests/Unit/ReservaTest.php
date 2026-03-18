<?php

namespace Tests\Unit;

use App\Models\Evento;
use App\Models\Entrada;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaTest extends TestCase
{
    use RefreshDatabase;


    /** @test 
     *  Comprueba que la reserva se guarda correctamente en la base de datos.
    */
    public function puede_crear_una_reserva_correctamente()
    {
        $usuario = User::factory()->create();
        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();

        $reserva = Reserva::factory()->for($entrada)->for($usuario)->create();

        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'user_id' => $usuario->id,
            'entrada_id' => $entrada->id,
        ]);
    }

    /** @test 
     *  Comprueba que las relaciones Eloquent funcionan.
    */
    public function reserva_pertenece_a_usuario_y_entrada()
    {
        $usuario = User::factory()->create();
        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();
        $reserva = Reserva::factory()->for($entrada)->for($usuario)->create();

        $this->assertEquals($usuario->id, $reserva->user->id);
        $this->assertEquals($entrada->id, $reserva->entrada->id);
    }
}