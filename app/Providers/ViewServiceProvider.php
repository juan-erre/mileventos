<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Ubicacion;
use App\Models\Categoria;
use App\Models\Evento;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View composer para esta vista (Estas variables siempre estarán presentes para esta vista)
        View::composer(['navPrincipal', 'navFormRegistroEvento', 'navFormUpdateEvento'], function ($view) {

            // Solo ejecutar si las tablas existen
            if (Schema::hasTable('eventos') &&
                Schema::hasTable('ubicaciones') &&
                Schema::hasTable('categorias')) {

                $ubicacionesGlobal = Ubicacion::all();
                $categoriasGlobal = Categoria::all(); 

                // Accede al request global
                $request = request();

                // Opcion del Select seleccionada
                $ubicacionSeleccionada = $request->ubicacion_id; // null si no existe
                $categoriaSeleccionada = $request->categoria_id; // null si no existe
                
                $query = Evento::with(['categoria', 'user', 'ubicacion']);

                // Filtro de eventos pasados
                if ($request->filled('eventos_pasados') && auth()->check()) {
                    $query->whereDate('fecha_fin', '<', today());

                    
                    if (auth()->user()->rol_id !== 1) {
                        // Solo el organizador ve sus eventos pasados
                        $query->where('user_id', auth()->id());
                    }
                } else {
                    // Eventos actuales o futuros
                    $query->whereDate('fecha_fin', '>=', today());
                }

                // Aplica filtros si vienen en GET (Si existen)
                if ($request->filled('ubicacion_id')) {
                    $query->where('ubicacion_id', $request->ubicacion_id);
                }
                if ($request->filled('categoria_id')) {
                    $query->where('categoria_id', $request->categoria_id);
                }

                if ($request->filled('mis_eventos') && auth()->check()) {
                    
                    /** @var \App\Models\User $user */
                    $user = auth()->user();

                    $query->where('user_id', $user->id);
                }
               
                // Eventos filtrados y ordenados por fecha_inicio
                $eventosGlobal = $query->orderBy('fecha_inicio', 'asc')->get();

                $view->with([
                    'eventosGlobal' => $eventosGlobal,
                    'ubicacionesGlobal' => $ubicacionesGlobal,
                    'categoriasGlobal' => $categoriasGlobal,
                    'ubicacionSeleccionada' => $ubicacionSeleccionada,
                    'categoriaSeleccionada'=> $categoriaSeleccionada
                ]);
            }
        });
    }
}