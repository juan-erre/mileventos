<?php

namespace App\Http\Controllers;
use App\Models\Evento;      
      
class PrincipalController extends Controller
{
    public function mostrarPrincipal()                 
    {     
        // Cada vez que se carga un endpoint, se ejecuta la limpieza y actualización de reservas. (Solución para no tener que configurar servidores)
        Evento::actualizarReservas();
        Evento::limpiarReservas();

        return view('navPrincipal');    // Se está haciendo uso de un Provider para tener siempre disponible variables en la vista 
    }  

    public function mostrarZonaPrivada()                 
    {  
        /** @var \App\Models\User $usuario */
        $usuario = auth()->user();  // No sería necesaria enviar $usuario, ya que Laravel ya hace disponible el usuario autenticado en todas las vistas mediante: auth()->user()

        // Obtener todas las reservas del usuario con la información del evento y entrada
        $reservas = $usuario->reservas()
            ->with(['entrada.evento', 'entrada'])
            ->orderByDesc('created_at') // opcional, mostrar las más recientes primero
            ->get();

        return view('navPrivada', compact('reservas'));
    } 
}





