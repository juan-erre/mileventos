@extends('plantillas.paginaPrincipal')  

@section('acceso_1')

    <a href="{{ route('principal') }}" class="boton_inicio boton">Inicio</a>

@endsection

@section('acceso_3')

    <a href="{{ route('gestionusuarios') }}" class="boton">Gestión de usuarios</a>

@endsection

@section('acceso_4')

    <a href="{{ route('formregistroevento') }}" class="boton" >Registrar evento</a>

@endsection

@section('contenido_2') 

    <h2 class="letrero">Gestión de usuarios</h2>

    @if (!isset ($userEdit))
         <form class='form_filtro' method="get" action="{{ route('gestionusuarios') }}">
            <!-- Select usuario -->
            <select name="rol_id">
                <option value="">Todos los usuarios</option>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}"
                        {{  $rolSeleccionado == $rol->id ? 'selected' : '' }}>
                        {{ $rol->rol }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Filtrar</button>
        </form>
    @endif

@endsection

@section('contenido_3') 

    @if(session('success'))
        <p class='mensaje_confir'><strong>{{ session('success') }}</strong></p>
    @endif
    @if ($errors->any())
        <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
    @endif

    @if (!isset ($userEdit))
        <div id="div_tabla_usuarios">
            <table id='tabla_usuarios' class="tabla_base">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuariosFiltrados as $user)
                        <tr>
                            <td><a href='{{ route('verusuario', $user) }}' >{{ $user->name }}</a></td>
                            <td><a href='{{ route('verusuario', $user) }}' >{{ $user->email }}</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay usuarios</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <!-- Se accede a la ficha del usuario -->
        <h2 class='letrero_importante' >{{ $userEdit->name }}</h2>

        <div class='secciones_datos_usuario'> 
            <div class="ficha_user tarjeta">
                <div>
                    <h3>{{ $userEdit->name }}</h3>
                    <h3>{{ $userEdit->email }}</h3>
                    <h3>{{ $userEdit->rol->rol }}</h3>
                </div>
                <div id='div_img_perfil'>
                @if($userEdit->foto)
                    <img id="img_perfil" src="{{ asset('imagenes/usuarios/' . $userEdit->foto) }}" alt="Foto de {{ $userEdit->name }}">
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

        <div class='secciones_datos_usuario'>
            <h2 class='letrero' >Editar datos del usuario</h2>
            <form class="tarjeta" method="POST" action="{{ route('usuario.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" value="{{ $userEdit->id }}">
                <div>
                    <label for="name">Nombre completo</label>
                    <input type="text" name="name" value="{{ $userEdit->name }}">
                </div>
                <div>
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" value="{{ $userEdit->email }}">
                </div>
                <div>
                    <label for="password">Nueva contraseña</label>
                    <input type="password" name="password" placeholder="Introduce tu contraseña">
                </div>
                <div>
                    <label  for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" placeholder="Confirma tu contraseña">
                </div>
                <div>
                    <label>Perfil de usuario</label>
                    @if ($userEdit->rol->id == 2)
                        <input type="text" value="{{ $userEdit->rol_id == 2 ? 'Organizador de eventos' : '' }}" disabled>
                    @else
                        <select name="rol">
                            <option value="" disabled selected hidden>Selecciona</option>
                            <option value="2" @selected($userEdit->rol_id == 2)>Organizador de eventos</option>
                            <option value="3" @selected($userEdit->rol_id == 3)>Usuario no organizador de eventos</option>
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

        <!-- Se confirma con JS antes de intentar eliminar -->
        <div class="secciones_datos_usuario">
            <h2 class="letrero">Dar de baja al usuario</h2>

            <form class="form_eliminar tarjeta" method="POST" action="{{ route('usuario.destroy') }}">
                @csrf
                @method('DELETE')

                <p>Escribe <strong>ELIMINAR</strong> para confirmar:</p>

                <input type="hidden" name="user_id" value="{{ $userEdit->id }}">
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