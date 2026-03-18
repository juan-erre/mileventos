<?php

namespace Tests\Unit;

use App\Models\Evento;
use App\Models\Entrada;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    /** @test 
     *  Verifica que el cálculo de disponibilidad funciona.
    */
    public function puede_calcular_entradas_libres()
    {
        $evento = Evento::factory()->create();
        $entradas = Entrada::factory()->count(5)->for($evento)->create();

        // Reservamos 2 entradas
        Reserva::factory()->for($entradas[0])->for(User::factory()->create())->create();
        Reserva::factory()->for($entradas[1])->for(User::factory()->create())->create();

        $this->assertEquals(3, $evento->entradasLibres());
    }

    /** @test 
     *  Comprueba que el evento cuenta correctamente cuántas reservas existen.
    */
    public function puede_contar_total_reservas()
    {
        $evento = Evento::factory()->create();
        $entradas = Entrada::factory()->count(3)->for($evento)->create();

        // Reservamos todas las entradas
        foreach ($entradas as $entrada) {
            Reserva::factory()->for($entrada)->for(User::factory()->create())->create();
        }

        $this->assertEquals(3, $evento->totalReservas());
    }

    /** @test 
     *  Comprueba que el método devuelve solo las reservas de ese usuario.
    */
    public function puede_calcular_entradas_reservadas_por_usuario()
    {
        $evento = Evento::factory()->create();
        $usuario = User::factory()->create();
        $entradas = Entrada::factory()->count(3)->for($evento)->create();

        Reserva::factory()->for($entradas[0])->for($usuario)->create();
        Reserva::factory()->for($entradas[1])->for(User::factory()->create())->create();

        $this->assertEquals(1, $evento->entradasReservadasPorUsuario($usuario->id));
    }
}