<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;    
use App\Models\Entrada;
use App\Models\Reserva;
use Carbon\Carbon;

class EventoController extends Controller 
{
    // Muetra la página del evento
    public function mostrarEvento($id)                 
    {  
        $evento = Evento::findOrFail($id);

        /** @var \App\Models\User $usuario */
        $usuario = auth()->user();
        $usuarioId = $usuario?->id;

        // Obtenemos la cantidad de entradas reservadas por el usuario
        $entradasReservadas = $evento->entradasReservadasPorUsuario($usuarioId);

        // Mensaje solo la primera vez
        if ($evento->reservas_habilitadas == false && !session()->has('mensaje_reservas')) {
            session()->flash('mensaje_reservas', 'Evento con reservas bloqueadas.');
        }

        // Solo organizador o admin pueden ver las reservas
        $reservas = collect(); // colección vacía por defecto
        if ($usuario && ($evento->user_id === $usuario->id || $usuario->isAdmin())) {
            $reservas = Reserva::with(['user','entrada'])
                ->whereHas('entrada', function ($q) use ($evento) {
                    $q->where('evento_id', $evento->id);
                })
                ->get();
        }

        // Reservas del usuario para el evento
        $reservasUser = $usuario
            ? $usuario->reservas()
                ->with(['entrada.evento', 'entrada'])
                ->whereHas('entrada', function ($q) use ($id) {
                    $q->where('evento_id', $id);
                })
                ->latest()
                ->get()
            : null;

        return view('navEvento', compact('evento', 'entradasReservadas', 'reservas', 'reservasUser'));;
    }  

    // Muestra el formulario de registro de un evento
    public function formRegistroEvento(Request $request)
    {
        return view('navFormRegistroEvento');   // Se está haciendo uso de un Provider para tener siempre disponible variables en la vista    
    }

    // Guarda el evento
    public function guardar(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|min:4|max:100',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            // No puede ser anterior a hoy
            'fecha_inicio' => 'required|date|after_or_equal:today',
            // Debe ser posterior o igual al inicio
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'num_entradas' => 'nullable|integer|min:1',
            'cartel' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'titulo.required' => 'El título del evento es obligatorio.',
            'titulo.min' => 'El título debe tener al menos 4 caracteres.',
            'titulo.string' => 'El título debe ser un texto válido.',
            'titulo.max' => 'El título no puede superar los 100 caracteres.',

            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',

            'ubicacion_id.required' => 'Debes seleccionar una ubicación.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no es válida.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',

            'fecha_fin.required' => 'La fecha de finalización es obligatoria.',
            'fecha_fin.date' => 'La fecha de finalización no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'descripcion.string' => 'La descripción debe ser un texto válido.',

            'num_entradas.integer' => 'El número de entradas debe ser un número entero.',
            'num_entradas.min' => 'Debe haber al menos 0 entrada disponible.',

            'cartel.image' => 'El archivo debe ser una imagen.',
            'cartel.mimes' => 'La imagen debe estar en formato jpg, jpeg, png o webp.',
            'cartel.max' => 'La imagen no puede superar los 2MB.',
        ]);

        // Validación de fecha de fin máxima de 3 meses
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);

        if ($fin->gt($inicio->copy()->addMonths(3))) {
            return back()
                ->withErrors(['fecha_fin' => 'La fecha de finalización no puede superar los 3 meses desde la fecha de inicio.'])
                ->withInput();
        }
        
        // Cartel por defecto
        $nombre = 'default/evento.jpg'; 
        
        if ($request->hasFile('cartel')) {
            $file = $request->file('cartel');
            $nombre = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes/eventos'), $nombre);
        }

          $evento = Evento::create([
            'user_id' => auth()->id(),
            'categoria_id' => $request->categoria_id,
            'ubicacion_id' => $request->ubicacion_id,
            'titulo' => $request->titulo,
            'cartel' => $nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'descripcion' => $request->descripcion,
            'num_entradas' => $request->num_entradas,
        ]);

        
        // Crear entradas automáticas
        if ($request->num_entradas > 0) {
            for ($i = 1; $i <= $request->num_entradas; $i++) {
                Entrada::create([
                    'evento_id' => $evento->id,
                ]);
            }
        }

        return redirect()->route('principal')->with('success', 'Registro completado correctamente.');
    }

    // Formulario de edición del evento 
    public function formUpdateEvento(Evento $evento)
    {      
       return view('navFormUpdateEvento', ['evento' => $evento, 'mensaje' => 'Importante: no se aplican modificaciones a la ubicación o fechas de un evento que tiene reservas.']);
    }

    // Actualiza el evento
    public function update(Request $request, Evento $evento)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Solo el creador del evento o un admin/organizador puede actualizar
        if ($evento->user_id !== $user->id && !$user->isAdmin() && !$user->isOrganizador()) { // En la ruta se verifica solo el acceso de admin y organizadores
            abort(403, 'No autorizado.');
        }       

        // Campos críticos que NO se pueden modificar si hay reservas
        $camposBloqueados = ['ubicacion_id', 'fecha_inicio', 'fecha_fin'];
        $tieneReservas = $evento->entradas()->whereHas('reserva')->exists();

       // Validaciones opcionales
        $request->validate([
            'titulo' => 'nullable|string|min:4|max:100',
            'categoria_id' => 'nullable|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',
            'fecha_inicio' => 'nullable|date|after_or_equal:today',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'num_entradas' => 'nullable|integer|min:0',
            'cartel' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'titulo.string' => 'El título debe ser un texto válido.',
            'titulo.min' => 'El título debe tener al menos 4 caracteres.',
            'titulo.max' => 'El título no puede superar los 100 caracteres.',

            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no es válida.',

            'fecha_inicio.date' => 'La fecha de inicio no es válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',

            'fecha_fin.date' => 'La fecha de finalización no es válida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'descripcion.string' => 'La descripción debe ser un texto válido.',

            'num_entradas.integer' => 'El número de entradas debe ser un número entero.',
            'num_entradas.min' => 'Debe haber al menos 1 entrada disponible.',

            'cartel.image' => 'El archivo debe ser una imagen.',
            'cartel.mimes' => 'La imagen debe estar en formato jpg, jpeg, png o webp.',
            'cartel.max' => 'La imagen no puede superar los 2MB.',
        ]);

        // Validación de fecha de fin máxima de 3 meses
        $inicio = Carbon::parse($request->fecha_inicio);
        $fin = Carbon::parse($request->fecha_fin);

        if ($fin->gt($inicio->copy()->addMonths(3))) {
            return back()
                ->withErrors(['fecha_fin' => 'La fecha de finalización no puede superar los 3 meses desde la fecha de inicio.'])
                ->withInput();
        }

        // No permite reducir el numero de entradas
        if ($request->filled('num_entradas') && $request->num_entradas < $evento->num_entradas) {
            return back()->withErrors([
                'num_entradas' => "No se puede reducir el número de entradas."
            ])->withInput();
        }

        // Crea nuevas entradas, solo si e aumentan
        if ($request->filled('num_entradas') && $request->num_entradas > $evento->num_entradas) {
            $nuevas = $request->num_entradas - $evento->num_entradas;
            for ($i = 0; $i < $nuevas; $i++) {
                $evento->entradas()->create();
            }
        }

        // Manejo de imagen 
        if ($request->hasFile('cartel')) {
            // Borrar la imagen anterior si existe y no es la por defecto ni pruebas
            if ($evento->cartel && $evento->cartel !== 'default/evento.jpg' && !str_starts_with($evento->cartel, 'pruebas/') && file_exists(public_path('imagenes/eventos/' . $evento->cartel))) {
                unlink(public_path('imagenes/eventos/' . $evento->cartel));
            }

            $file = $request->file('cartel');
            $nombre = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes/eventos'), $nombre);
            $evento->cartel = $nombre;
        }

        // Actualizar solo los campos permitidos
        $campos = ['titulo', 'categoria_id', 'ubicacion_id', 'fecha_inicio', 'fecha_fin', 'descripcion', 'num_entradas'];
        foreach ($campos as $campo) {
            // Saltar campos críticos si hay reservas
            if ($tieneReservas && in_array($campo, $camposBloqueados)) {
                continue;
            }
            if ($request->filled($campo)) {
                $evento->$campo = $request->$campo;
            }
        }

        $evento->save();

        return redirect()->route('mostrarevento', $evento->id)->with('success', 'Evento actualizado correctamente.');
    }

    // Elimina el evento
    public function destroy(Evento $evento)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Solo el creador del evento o un admin/organizador puede actualizar
        if ($evento->user_id !== $user->id && !$user->isAdmin() && !$user->isOrganizador()) { // En la ruta se verifica solo el acceso de admin y organizadores
            abort(403, 'No autorizado.');
        }

        // Verificar si hay entradas reservadas
        $entradasReservadas = $evento->entradas()->whereHas('reserva')->count();
        if ($entradasReservadas > 0) {
            return back()->withErrors(["No se puede eliminar el evento: tiene $entradasReservadas entrada(s) reservada(s)."]);
        }

        // Borrar la imagen si no es la por defecto
        if ($evento->cartel && $entradasReservadas <=0 && $evento->cartel !== 'default/evento.jpg' && !str_starts_with($evento->cartel, 'pruebas/') && file_exists(public_path('imagenes/eventos/' . $evento->cartel))) {
            unlink(public_path('imagenes/eventos/' . $evento->cartel));
        }

        $evento->delete();

        return redirect()->route('principal')->with('success', 'Evento eliminado correctamente.');
    }
}





