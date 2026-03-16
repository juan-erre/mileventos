<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;      
use App\Models\Reserva; 

class reservaController extends Controller 
{
     // Método para reservar entradas
      public function reservar(Request $request, Evento $evento)
    {
        // Verifica que las reservas están activas
        if (!$evento->reservas_habilitadas) {
            return back()->withErrors(['Las reservas para este evento están actualmente bloqueadas.']);
        }

        /** @var \App\Models\User $usuario */
        $usuario = auth()->user();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para reservar entradas.');
        }

        // Validación de la cantidad
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        $cantidad = $request->input('cantidad');

        // Contamos las entradas disponibles
        $entradasDisponibles = $evento->entradas()
            ->whereDoesntHave('reserva')
            ->count();

        if ($entradasDisponibles < $cantidad) {
            return back()->withErrors(["Solo hay $entradasDisponibles entradas disponibles."]);
        }

        // Obtenemos las entradas libres y creamos reservas
        $entradasLibres = $evento->entradas()
            ->whereDoesntHave('reserva')
            ->limit($cantidad)
            ->get();

        foreach ($entradasLibres as $entrada) {
            $entrada->reserva()->create([
                'user_id' => $usuario->id,
            ]);
        }

        return back()->with('success', "Has reservado $cantidad entrada(s) correctamente.");
    }

   // Cancelar una reserva por ID
    public function cancelarReserva(Reserva $reserva)
    {
        /** @var \App\Models\User $usuario */
        $usuario = auth()->user();

        // Obtener el evento relacionado
        $evento = $reserva->entrada->evento;

        // Comprobar permisos: usuario que hizo la reserva, organizador o admin
        $puedeCancelar = 
            $usuario->id === $reserva->user_id ||     // Usuario que hizo la reserva
            $usuario->id === $evento->user_id ||     // Organizador del evento
            $usuario->isAdmin();                     // Administrador

        if (!$puedeCancelar) {
            abort(403, 'No tienes permiso para cancelar esta reserva.');
        }

        // Restricción: no cancelar si el evento ya comenzó
        if ($evento->fecha_inicio->isPast()) {
            return back()->withErrors([
                'mensaje' => 'No se puede cancelar reservas de un evento que ya comenzó.'
            ]);
        }

        // Eliminar la reserva
        $reserva->delete();

        return back()->with('success', 'Reserva cancelada correctamente.');
    }

    // Bloquea reservas de un evento
    public function bloquearReservas(Request $request, Evento $evento)
    {
        // Solo el organizador o admin puede bloquear
        /** @var \App\Models\User $usuario */
        $usuario = auth()->user();

        if ($evento->user_id !== $usuario->id && !$usuario->isAdmin()) {
            abort(403, 'No autorizado.');
        }

        $habilitar = $request->input('bloquear') == 0;
        // No permitir habilitar reservas si el evento ya terminó
        if ($habilitar && $evento->fecha_fin->isPast()) {
            return back()->withErrors([
                'mensaje' => 'No se pueden habilitar reservas para un evento que ya ha finalizado.'
            ]);
        }

        $evento->reservas_habilitadas = $habilitar;

        $evento->save();

        $mensaje = $evento->reservas_habilitadas ? 'Reservas habilitadas.' : 'Reservas bloqueadas.';

        return back()->with('success', $mensaje);
    }
}


   






