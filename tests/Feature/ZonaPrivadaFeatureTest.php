<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ZonaPrivadaFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_autenticado_puede_ver_zona_privada()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $user = User::factory()->for($rol)->create();

        $response = $this->actingAs($user)
            ->get(route('zonaprivada'));

        $response->assertStatus(200);
    }

    /** @test */
    public function usuario_no_autenticado_es_redirigido_a_login()
    {
        $response = $this->get(route('zonaprivada'));
        $response->assertRedirect('/');
    }
}