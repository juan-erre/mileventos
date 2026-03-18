<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Evento;
use App\Models\Entrada;
use App\Models\Reserva;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_obtener_todas_sus_reservas()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $usuario = User::factory()->for($rol)->create();
        $evento = Evento::factory()->create();
        $entradas = Entrada::factory()->count(2)->for($evento)->create();

        Reserva::factory()->for($entradas[0])->for($usuario)->create();
        Reserva::factory()->for($entradas[1])->for($usuario)->create();

        $this->assertCount(2, $usuario->reservas);
    }

    /** @test */
    public function puede_verificar_roles()
    {
        $adminRol = Rol::factory()->create(['rol' => 'Administrador']);
        $usuario = User::factory()->for($adminRol)->create();

        $this->assertTrue($usuario->isAdmin());
        $this->assertFalse($usuario->isOrganizador());
    }
}