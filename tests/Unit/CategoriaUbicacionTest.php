<?php

namespace Tests\Unit;

use App\Models\Categoria;
use App\Models\Ubicacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriaUbicacionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_categoria_y_ubicacion()
    {
        $categoria = Categoria::factory()->create(['categoria' => 'Concierto']);
        $ubicacion = Ubicacion::factory()->create(['provincia' => 'Madrid']);

        $this->assertDatabaseHas('categorias', ['categoria' => 'Concierto']);
        $this->assertDatabaseHas('ubicaciones', ['provincia' => 'Madrid']);
    }
}