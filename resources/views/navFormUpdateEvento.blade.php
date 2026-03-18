@extends('plantillas.paginaPrincipal')  

@section('acceso_1')

    @auth
        <a href="{{ route('principal') }}" class="boton_inicio boton">Inicio</a>
    @endauth
    
@endsection

@section('acceso_2')

    <a href="{{ route('formregistro') }}" class="boton">Regístrate</a>

@endsection

@section('acceso_3')

    <a href="{{ route('gestionusuarios') }}" class="boton">Gestión de usuarios</a>

@endsection

@section('acceso_4')

    <a href="{{ route('formregistroevento') }}" class="boton" >Registrar evento</a>

@endsection

@section('contenido_2') 
    
    <h2 class="letrero">Edita tu evento</h2>

@endsection

@section('contenido_3') 

      <form class='tarjeta' method="POST" action="{{ route('evento.update', $evento->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if(session('success'))
            <p class='mensaje_confir'><strong>{{ session('success') }}</strong></p>
        @endif
        @if ($errors->any())
            <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
        @endif
        @if(isset($mensaje) && $evento->entradas()->whereHas('reserva')->exists())
            <p class="mensaje_error"><strong>{{ $mensaje }}</strong></p>
        @endif

        <div>
            <label for="name">Título</label>
            <input type="text" id="titulo" name="titulo"  value="{{ $evento->titulo }}">
        </div>

        <!-- Select Ubicación -->
        <label for="ubicacion_id">Localización</label>
        <select name="ubicacion_id">
            <option value="" disabled hidden>Provincia</option>             
            @foreach($ubicacionesGlobal as $ubicacion)
                <option value="{{ $ubicacion->id }}"
                    {{ $evento->ubicacion->id == $ubicacion->id ? 'selected' : '' }}>
                    {{ $ubicacion->provincia }}
                </option>
            @endforeach
        </select>

        <!-- Select Categoría -->
        <label for="categoria_id">Tipo de evento</label>
        <select name="categoria_id">
            <option value="" disabled hidden>Categorías</option>
            @foreach($categoriasGlobal as $categoria)
                <option value="{{ $categoria->id }}"
                    {{ $evento->categoria->id == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->categoria }}
                </option>
            @endforeach
        </select>

         <!-- Fechas -->
        <div>
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" name="fecha_inicio" value="{{ $evento->fecha_inicio->format('Y-m-d') }}">
        </div>

        <div>
            <label for="fecha_fin">Fecha fin</label>
            <input type="date" name="fecha_fin" value="{{ $evento->fecha_fin->format('Y-m-d') }}">
        </div>

        <!-- Descripción -->
        <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" rows="4" placeholder="Describe el evento">{{ $evento->descripcion }}</textarea>
        </div>

        <!-- Número de entradas -->
        <div>
            <label for="num_entradas">Número de entradas</label>
            <input type="number" name="num_entradas" min="0" value="{{ $evento->num_entradas }}">
        </div>

        <div>
            <label for="cartel">Foto de portada</label>
            <input type="file" id="cartel" name="cartel" accept="image/*">
        </div>
        <button type="submit">Guadar cambios</button>
    </form>

@endsection