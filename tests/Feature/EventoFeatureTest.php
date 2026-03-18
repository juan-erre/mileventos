<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Rol;
use App\Models\Evento;
use App\Models\Categoria;
use App\Models\Ubicacion;
use App\Models\Entrada;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventoFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function organizador_puede_crear_evento()
    {
        $rol = Rol::factory()->create(['rol' => 'Organizador']);
        $organizador = User::factory()->for($rol)->create();

        $categoria = Categoria::factory()->create();
        $ubicacion = Ubicacion::factory()->create();

        $response = $this->actingAs($organizador)
            ->post(route('evento.guardar', []), [
                'titulo' => 'Concierto Test',
                'descripcion' => 'Evento de prueba',
                'fecha_inicio' => now()->addDays(5)->format('Y-m-d'),
                'fecha_fin' => now()->addDays(6)->format('Y-m-d'),
                'categoria_id' => $categoria->id,
                'ubicacion_id' => $ubicacion->id,
                'num_entradas' => 100,
            ]);

        $response->assertStatus(302); // redirige tras guardar
        $this->assertDatabaseHas('eventos', ['titulo' => 'CONCIERTO TEST']); // se guarda en mayúsculas
    }

    /** @test */
    public function usuario_normal_no_puede_crear_evento()
    {
        $rol = Rol::factory()->create(['rol' => 'Registrado']);
        $user = User::factory()->for($rol)->create();

        $response = $this->actingAs($user)
            ->post(route('evento.guardar', []), [
                'titulo' => 'Evento Prohibido',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function evento_puede_mostrarse_publicamente()
    {
        $rol = Rol::factory()->create(['rol' => 'Organizador']);
        $user = User::factory()->for($rol)->create();

        $categoria = Categoria::factory()->create();
        $ubicacion = Ubicacion::factory()->create();

        $evento = Evento::factory()->create([
            'user_id' => $user->id,
            'categoria_id' => $categoria->id,
            'ubicacion_id' => $ubicacion->id,
            'titulo' => 'Concierto Test',
            'num_entradas' => 1,
        ]);

        // Crear una entrada obligatoria para que la vista no falle
        $entrada = Entrada::factory()->for($evento)->create();

        // Loguearse como cualquier usuario
        $response = $this->actingAs($user)->get(route('mostrarevento', $evento->id));

        $response->assertStatus(200);
        $response->assertSee('CONCIERTO TEST'); // se convierte a mayúsculas por el mutator
    }
}