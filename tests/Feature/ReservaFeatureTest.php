<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Rol;
use App\Models\Evento;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\Entrada;
use App\Models\Reserva;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservaFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_puede_cancelar_reserva()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $user = User::factory()->for($rol)->create();

        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();
        $reserva = Reserva::factory()->for($entrada)->for($user)->create();

        $response = $this->actingAs($user)
            ->delete(route('reserva.cancelar', $reserva->id));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
    }

    /** @test */
    public function usuario_solo_ve_sus_reservas()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $user1 = User::factory()->for($rol)->create();
        $user2 = User::factory()->for($rol)->create();

        $evento = Evento::factory()->create();
        $entrada = Entrada::factory()->for($evento)->create();

        Reserva::factory()->for($entrada)->for($user1)->create();
        Reserva::factory()->for($entrada)->for($user2)->create();

        $response = $this->actingAs($user1)
            ->get(route('zonaprivada'));

        $response->assertStatus(200);
        $response->assertDontSee($user2->name);
    }
}