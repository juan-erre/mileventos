@extends('plantillas.paginaPrincipal')  

@section('acceso_1')

    <a href="{{ route('principal') }}" class="boton_inicio boton">Inicio</a>

@endsection

@section('acceso_3')

    <a href="{{ route('gestionusuarios') }}" class="boton">Gestión de usuarios</a>

@endsection

@section('contenido_2') 

    <h2 class="letrero">Datos personales</h2>

@endsection

@section('acceso_4')

    <a href="{{ route('formregistroevento') }}" class="boton" >Registrar evento</a>

@endsection

@section('contenido_3') 

    <div class='secciones_datos_usuario'> 
        <div class='ficha_user tarjeta'>
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <h3>{{ auth()->user()->email }}</h3>
                @if (auth()->user()->isAdmin() || auth()->user()->isOrganizador())
                    <h3>{{ auth()->user()->rol->rol }}</h3>
                @endif
            </div>
            <div id='div_img_perfil'>
            @if(auth()->user()->foto)
                <img id="img_perfil" src="{{ asset('imagenes/usuarios/' . auth()->user()->foto) }}" alt="Foto de {{ auth()->user()->name }}">
            @else
                Sin foto
            @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <p class='mensaje_confir'><strong>{{ session('success') }}</strong></p>
    @endif
    @if ($errors->any())
        <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
    @endif

    @if($reservas->isNotEmpty())
        <h2 class='letrero' >Mis entradas reservas</h2>
        <div class='secciones_datos_usuario div_tabla_responsiva'>
            <table class='tabla_base'>
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->entrada->evento->titulo }}</td>
                            <td>{{ $reserva->entrada->evento->fecha_inicio->format('d/m/Y') }}</td>
                            <td>
                                <form method="POST" action="{{ route('reserva.cancelar', $reserva->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton_riesgo" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No tienes reservas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
    <div class='secciones_datos_usuario'>
        <h2 class='letrero' >Editar mis datos</h2>
        <form class='tarjeta' method="POST" action="{{ route('usuario.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="name">Nombre completo</label>
                @if (auth()->user()->isAdmin())
                    <input type="text" name="name" value="{{ auth()->user()->name }}" disabled>
                @else
                    <input type="text" name="name" value="{{ auth()->user()->name }}">
                @endif
            </div>
            <div>
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" value="{{ auth()->user()->email }}" disabled>
            </div>
            <div>
                <label for="password">Nueva contraseña (opcional)</label>
                <input type="password" name="password" placeholder="Introduce tu contraseña">
            </div>
            <div>
                <label  for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" placeholder="Confirma tu contraseña">
            </div>
            <div>
                <label>Perfil de usuario</label>
                @if (auth()->user()->isAdmin() || auth()->user()->isOrganizador())
                    <input type="text" value="{{ auth()->user()->rol_id == 2 ? 'Organizador de eventos' : 'Administrador' }}" disabled>
                @else
                    <select name="rol">
                        <option value="" disabled selected hidden>Selecciona</option>
                        <option value="2" @selected(auth()->user()->rol_id == 2)>Organizador de eventos</option>
                        <option value="3" @selected(auth()->user()->rol_id == 3)>Usuario no organizador de eventos</option>
                    </select>
                @endif
            </div>
            <div>
                <label for="foto">Foto de perfil</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>    

            <button type="submit">Guardar cambios</button>
        </form>
    </div>

    @if (auth()->user()->isAdmin())

    @else
    <!-- Se confirma con JS antes de intentar eliminar -->
    <div class="secciones_datos_usuario">
        <h2 class="letrero">Dar de baja mi cuenta</h2>

        <form class="form_eliminar tarjeta" method="POST" action="{{ route('usuario.destroy') }}">
            @csrf
            @method('DELETE')

            <p>Escribe <strong>ELIMINAR</strong> para confirmar:</p>

            <input type="text" name="confirmacion" id="confirmacion" placeholder="Escribe ELIMINAR">
            <p id="errorConfirmacion" style="color:red; display:none;"><strong> Escribir ELIMINAR para confirmar.</strong></p>

            @if (session('error'))
                <p class="mensaje_error"><strong>{{ session('error') }}</strong></p>
            @endif

            <button type="submit" id="btnEliminar" class="boton_riesgo" disabled>Eliminar cuenta</button>
        </form>
    </div>
    @endif

@endsection
