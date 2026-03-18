@extends('plantillas.paginaPrincipal')  

@section('acceso_1')

    @auth
        <a href="{{ route('zonaprivada') }}" class="boton">Área privada</a>
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

    <h2 class="letrero">Próximos eventos...</h2>
    
    <form class='form_filtro' method="get" action="{{ route('principal') }}">
        <!-- Select Ubicación -->
        <label for="ubicacion_id" class="sr-only">Ubicación</label>
        <select name="ubicacion_id" id="ubicacion_id">
            <option value="">Provincias (todas)</option>
            @foreach($ubicacionesGlobal as $ubicacion)
                <option value="{{ $ubicacion->id }}"
                    {{ $ubicacionSeleccionada == $ubicacion->id ? 'selected' : '' }}>
                    {{ $ubicacion->provincia }}
                </option>
            @endforeach
        </select>

        <!-- Select Categoría -->
        <label for="categoria_id" class="sr-only">Categoría</label>
        <select name="categoria_id" id="categoria_id">
            <option value="">Categorías (todas)</option>
            @foreach($categoriasGlobal as $categoria)
                <option value="{{ $categoria->id }}"
                    {{  $categoriaSeleccionada == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->categoria }}
                </option>
            @endforeach
        </select>

        <button type="submit">Filtrar</button>
    </form>
    
    @auth
        @if(auth()->user()->isAdmin() || auth()->user()->isOrganizador())
            <div id="div_accesos_eventos_org">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('principal', ['eventos_pasados' => 1]) }}" class="boton">Todos eventos pasados</a>
                @else 
                    <a href="{{ route('principal', ['eventos_pasados' => 1]) }}" class="boton">Ver mis eventos pasados</a>
                @endif
                <a href="{{ route('principal', ['mis_eventos' => 1]) }}" class="boton">Ver mis eventos</a>
                <a href="{{ route('principal') }}" class="boton">Ver todos</a>
            </div>
        @endif
    @endauth

    
@endsection

@section('contenido_3') 

    @if(session('success'))
        <p class='mensaje_confir'><strong>{{ session('success') }}</strong></p>
    @endif
    @if ($errors->any())
        <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
    @endif

    <!-- forelse sustituye a isset + foreach y la variable $eventosGlobal siempre esta presente para esta plantilla al utilizar un provider -->
    @forelse($eventosGlobal as $evento)
        <div id="div_capsula" class="tarjeta">
            <a href="{{ route('mostrarevento', $evento->id) }}" class="capsula">
                <div class="div_superior_capsula">
                    <div class="nombre_evento_capsula">
                        <h3>{{ $evento->titulo }}</h3>
                    </div>
                    <div>
                        <img class="imagen_link" src="{{ asset('imagenes/diseno/link.png') }}" alt="{{ $evento->titulo }}">
                    </div>
                </div>

                <div class="div_info_capsula">
                    <div class="div_imagen_capsula">
                        <img class="imagen_evento" src="{{ asset('imagenes/eventos/' . $evento->cartel) }}" alt="{{ $evento->titulo }}" fetchpriority=high>
                    </div>
                
                    <div class="div_calendario_capsula">
                        @foreach ($evento->mesesEvento as $mesData)
                            <!-- Llamada al componente para generar el caledario visual -->
                            <x-calendario :mesData="$mesData" />
                        @endforeach
                        
                        <p class="informacion_capsula"><strong>Provincia:</strong> {{$evento->ubicacion->provincia}}</p>
                        <p class="informacion_capsula"><strong>Tipo:</strong> {{$evento->categoria->categoria}}</p>
                        <p class="informacion_capsula"><strong>Organiza:</strong> {{$evento->user->name}}</p>
                    </div>
                                    
                </div>
            </a>

            @auth
                @if($evento->entradasReservadasPorUsuario(auth()->user()->id) > 0)
                    <div class="div_gest_evento tarjeta">
                        <p class="mensaje_info"><strong>Tus entradas: {{ $evento->entradasReservadasPorUsuario(auth()->user()->id) }}</strong></p>
                     </div>
                @endif

                @if (auth()->user()->isAdmin() || (auth()->user()->isOrganizador() && auth()->user()->id == $evento->user_id))
                    <div class="div_gest_evento tarjeta">

                        <a href="{{ route('formupdateevento', $evento->id) }}" class="boton" >Editar evento</a>

                        <form method="POST" action="{{  route('evento.destroy', $evento->id)  }}">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" class="boton_riesgo" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                Eliminar evento
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

        </div>

    @empty
        <p>No hay eventos disponibles.</p>
    @endforelse

@endsection