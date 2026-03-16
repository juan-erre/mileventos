<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;

class UserController extends Controller
{
    // Muestra el formulario
    public function formRegistro()
    {
        return view('navFormRegistro');
    }

    // Guarda el usuario
    public function guardar(Request $request)
    {
        $request->validate([  // Envía los errores automáticamente si falla.
            'name' => 'required|string|min:4|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'rol' => 'required|in:2,3',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' // Solo imágenes, tipo mimes, 2MB máximo
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 4 caracteres.',
            'name.string' => 'El nombre debe ser un texto.',
            'name.max' => 'El nombre no puede superar los 100 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.max' => 'El correo electrónico no puede superar los 100 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol.required' => 'Debe seleccionar un perfil de usuario.',
            'rol.in' => 'El perfil seleccionado no es válido.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser jpg, jpeg, png o webp.',
            'foto.max' => 'La imagen no puede superar los 2MB.',
        ]);
        
        // Foto por defecto para el defecto
        $nombre = 'default/perfil.png'; 
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombre = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('imagenes/usuarios'), $nombre);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Ya se realiza cast 'hashed' en el modelo
            'rol_id' => $request->rol,
            'foto' => $nombre, // Será 'perfil.png' si no subió foto
        ]);

        auth()->login($user);

        return redirect()->route('principal');
    }

    // Actualizar datos del usuario
    public function update(Request $request)
    {
        if (auth()->user()->rol_id == 1 && $request->filled('user_id')) {
            // admin editando otro usuario
            $user = User::findOrFail($request->input('user_id'));
        } else {
            // Usuario editando su propio perfil
            $user = auth()->user();
        }

        $request->validate([
            'name' => 'sometimes|required|min:4|string|max:100',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'rol' => 'sometimes|required|in:2,3',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.string' => 'El nombre debe ser un texto.',
            'name.min' => 'El nombre debe tener al menos 4 caracteres.',
            'name.max' => 'El nombre no puede superar los 100 caracteres.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.max' => 'El correo electrónico no puede superar los 100 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol.in' => 'El perfil seleccionado no es válido.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser jpg, jpeg, png o webp.',
            'foto.max' => 'La imagen no puede superar los 2MB.',
        ]);

        if ($request->filled('name') && !(auth()->user()->rol->id == 1 && $user->id == auth()->id())) {
            $user->name = $request->name;
        }

        if ($request->filled('email') && auth()->user()->rol->id == 1 && $user->id != auth()->id()) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        // Solo un usuario registrado puede cambiar su rol
        if ($request->filled('rol') && auth()->user()->rol->id == 3) {
            $user->rol_id = $request->rol;
        }
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nombre = time() . '_' . $file->getClientOriginalName();

            // Subir la nueva foto
            $file->move(public_path('imagenes/usuarios'), $nombre);

            // Eliminar la foto antigua si existe y no es la por defecto ni de pruebas
            if ($user->foto && $user->foto !== 'default/perfil.png' && file_exists(public_path('imagenes/usuarios/' . $user->foto)) && !str_starts_with($user->foto, 'pruebas/')) {
                unlink(public_path('imagenes/usuarios/' . $user->foto));
            }

            // Guarda el nombre de la nueva foto
            $user->foto = $nombre;
        }

        $user->save(); 

        return back()->with('success', 'Datos actualizados correctamente.');
    }

    // Elimina el usuario
    public function destroy(Request $request)
    {
        // Para confirmación
        if ($request->confirmacion !== 'ELIMINAR') {
            return back()->with('error', 'Debes escribir ELIMINAR para confirmar.');
        }

        $authUser = auth()->user();

        // ADMIN
        if ($authUser->rol_id == 1 && $request->filled('user_id')) {

            $user = User::findOrFail($request->input('user_id'));

            // Evitar que el admin se elimine a sí mismo
            if ($user->id == $authUser->id) {
                return back()->with('error', 'No puedes eliminar tu propio usuario administrador.');
            }

            // Comprobar si tiene eventos con reservas
            $eventosConReservas = $user->eventos()
                ->whereHas('entradas.reserva')
                ->exists();

            if ($eventosConReservas) {
                return back()->with('error', 'No se puede eliminar este usuario porque tiene eventos con reservas.');
            }

            $user->delete();

            // Elimina la foto de perfil pero no la default ni pruebas
            if ($user->foto && $user->foto !== 'default/perfil.png' && !str_starts_with($user->foto, 'pruebas/')) {
                $fotoPath = public_path('imagenes/usuarios/' . $user->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath); // elimina el archivo
                }
            }

            return redirect()->route('gestionusuarios')->with('success', 'Cuenta eliminada correctamente.');
        }

        // USUARIO NO admin)
        /** @var \App\Models\User $authUser */  // Esta linea es simplemente para evitar que VS Code (Intelephense) lo marque en rojo, cuando no es realmente un error  
        if ($authUser->rol_id != 1) {
            
            // Comprobar si tiene eventos con reservas
            $eventosConReservas = $authUser->eventos()
                ->whereHas('entradas.reserva')
                ->exists();

            if ($eventosConReservas) {
                return back()->with('error', 'No se puede eliminar este usuario porque tiene eventos con reservas.');
            }

            $authUser->delete();

            // Elimina la foto de perfil pero no la default
            if ($authUser->foto && $authUser->foto !== 'default/perfil.png' && !str_starts_with($authUser->foto, 'pruebas/')) {
                $fotoPath = public_path('imagenes/usuarios/' . $authUser->foto);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath); // elimina el archivo
                }
            }

            // Cierra sesión
            auth()->logout();

            return redirect('/')
                ->with('success', 'Cuenta eliminada correctamente.');
        }

        // Si llega aquí, es un admin intentando eliminarse
        return back()->with('error', 'No puedes eliminar tu propio usuario administrador.');
    }

    // Gestión de usuarios por el admin
    public function gestionUsuarios(Request $request)
    {
        // Todos los usuarios excepto el administrador
        $roles = Rol::where('id', '!=', 1)->get();

        // Opcion del Select seleccionada
        $rolSeleccionado = $request->input('rol_id'); // null si no existe

        // Filtro
        $usuariosFiltrados = User::query();
        // Siempre excluye al admin
        $usuariosFiltrados->where('rol_id', '!=', 1);

        // Si hay un rol seleccionado por el filtro
        if ($rolSeleccionado) {
            $usuariosFiltrados->where('rol_id', $rolSeleccionado);
        }

        $usuariosFiltrados = $usuariosFiltrados->get();

        return view('navGestionUsuarios', compact('roles', 'rolSeleccionado', 'usuariosFiltrados'));
    }

    // Editar y eliminar un usuario como Administrador
    public function verUsuario(User $user)
    {
        $userEdit = $user;

        return view('navGestionUsuarios', compact('userEdit'));
    }
}





