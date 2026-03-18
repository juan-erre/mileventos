@extends('plantillas.paginaPrincipal') 

@section('acceso_1')
       
    <a href="{{ route('principal') }}" class="boton_inicio boton">Inicio</a>

@endsection

@section('contenido_2') 
    
    <h2 class="letrero">Formulario de registro</h2>

@endsection

@section('contenido_3') 

    <form class='tarjeta' method="POST" action="{{ route('registro.guardar') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
        @endif

        <div>
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name"  value="{{ old('name') }}" placeholder="Introduce tu nombre" required>
        </div>
        <div>
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Introduce tu correo" required>
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>
        </div>
        <div>
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" required>
        </div>
        <div>
            <label>Perfil de usuario</label>
            <select name="rol" required>
                <option value="" disabled selected hidden>Selecciona</option>
                <option value="2" @selected(old('rol') == 2)>Organizador de eventos</option>
                <option value="3" @selected(old('rol') == 3)>Usuario no organizador de eventos</option>
            </select>
        </div>
        <div>
            <label for="foto">Foto de perfil</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
        <button type="submit">Registrar</button>
    </form>

@endsection