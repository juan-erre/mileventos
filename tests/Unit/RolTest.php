<?php

namespace Tests\Unit;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_un_rol()
    {
        $rol = Rol::factory()->create(['rol' => 'Administrador']);
        $this->assertDatabaseHas('roles', ['id' => $rol->id, 'rol' => 'Administrador']);
    }

    /** @test */
    public function un_rol_tiene_usuarios()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $usuario = User::factory()->for($rol)->create();

        $this->assertCount(1, $rol->users);
        $this->assertEquals($usuario->id, $rol->users->first()->id);
    }
}