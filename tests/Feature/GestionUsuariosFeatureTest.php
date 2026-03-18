<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GestionUsuariosFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function administrador_puede_ver_gestion_usuarios()
    {
        $rolAdmin = Rol::factory()->create(['rol' => 'Administrador']);
        $admin = User::factory()->for($rolAdmin)->create();

        $response = $this->actingAs($admin)
            ->get(route('gestionusuarios'));

        $response->assertOk();
    }

    /** @test */
    public function usuario_no_admin_no_puede_ver_gestion_usuarios()
    {
        $rolUser = Rol::factory()->create(['rol' => 'Registrado']);
        $user = User::factory()->for($rolUser)->create();

        $response = $this->actingAs($user)
            ->get(route('gestionusuarios'));

        $response->assertStatus(403);
    }
}