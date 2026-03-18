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

@section('contenido_2') 
    
    <h2 class="letrero">Publicar un evento</h2>

@endsection

@section('contenido_3') 

      <form class='tarjeta' method="POST" action="{{ route('evento.guardar') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
        @endif

        <div>
            <label for="name">Título</label>
            <input type="text" id="titulo" name="titulo"  value="{{ old('titulo') }}" placeholder="Introduce el título" required>
        </div>

        <!-- Select Ubicación -->
        <label for="name">Localización</label>
        <select name="ubicacion_id" required>
            <option value="" disabled selected hidden>Provincia</option>             
            @foreach($ubicacionesGlobal as $ubicacion)
                <option value="{{ $ubicacion->id }}"
                    {{ old('ubicacion_id', $ubicacionSeleccionada) == $ubicacion->id ? 'selected' : '' }}>
                    {{ $ubicacion->provincia }}
                </option>
            @endforeach
        </select>

        <!-- Select Categoría -->
        <label for="name">Tipo de evento</label>
        <select name="categoria_id" required>
            <option value="" disabled selected hidden>Categorías</option>
            @foreach($categoriasGlobal as $categoria)
                <option value="{{ $categoria->id }}"
                    {{  old('categoria_id', $categoriaSeleccionada) == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->categoria }}
                </option>
            @endforeach
        </select>

         <!-- Fechas -->
        <div>
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
        </div>

        <div>
            <label for="fecha_fin">Fecha fin</label>
            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
        </div>

        <!-- Descripción -->
        <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" rows="4" placeholder="Describe el evento">{{ old('descripcion') }}</textarea>
        </div>

        <!-- Número de entradas -->
        <div>
            <label for="num_entradas">Número de entradas</label>
            <input type="number" name="num_entradas" min="0" value="{{ old('num_entradas') }}">
        </div>

        <div>
            <label for="cartel">Foto de portada</label>
            <input type="file" id="cartel" name="cartel" accept="image/*">
        </div>
        <button type="submit">Publicar evento</button>
    </form>

@endsection