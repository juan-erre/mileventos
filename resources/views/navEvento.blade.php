@extends('plantillas.paginaPrincipal')  

@section('acceso_1')

    <a href="{{ route('principal') }}" class="boton_inicio boton">Inicio</a>

@endsection

@section('acceso_3')

    <a href="{{ route('gestionusuarios') }}" class="boton">Gestión de usuarios</a>

@endsection

@section('contenido_2') 

    <h2 class="letrero_importante">{{ $evento->titulo }}</h2>

@endsection

@section('acceso_4')

    <a href="{{ route('formregistroevento') }}" class="boton" >Registrar evento</a>

@endsection

@section('contenido_3') 

    @auth

        @if(session('success'))
            <p class='mensaje_confir'><strong>{{ session('success') }}</strong></p>
        @endif
        @if ($errors->any())
            <p class='mensaje_error'><strong>{{ $errors->first() }}</strong></p>
        @endif
        @if(!session('success') && !$errors->any())
            <p class="mensaje_error"><strong>{{ session('mensaje_reservas') }}</strong></p>
        @endif

    @endauth

    <div id='div_principal_evento' class='tarjeta'>
        <div id="div_imagen_evento">
            <img class="imagen_evento" src="{{ asset('imagenes/eventos/' . $evento->cartel) }}" alt="{{ $evento->titulo }}">
        </div>

        <div id="div_calendario_evento">
            <div>
                @foreach ($evento->mesesEvento as $mesData)
                    <!-- Llamada al componente para generar el caledario visual -->
                    <x-calendario :mesData="$mesData" />
                @endforeach
            </div>
            <div>
                <p><strong>Provincia:</strong> {{$evento->ubicacion->provincia}}</p>
                <p><strong>Tipo de evento:</strong> {{$evento->categoria->categoria}}</p>
                <p><strong>Organiza:</strong> {{$evento->user->name}}</p>
            </div>
        </div>
        <div id="div_masinfo_evento">
            <p><strong>Más información:</strong> {{$evento->descripcion}}</p>
            @if ($evento->num_entradas > 0)
                <div class="div_gest_reserva">
                    <p><strong>Entradas disponibles:</strong> {{ $evento->entradasLibres() }}</p>
                    @auth
                    <form class="form_reserva" method="POST" action="{{ route('reservar', $evento->id) }}">
                        @csrf

                        <input type="number" name="cantidad" min="1" value="1" required>
                        
                        <button type="submit">Reservar</button>
                      </form>
                    @endauth
                </div>
                                
                @auth
                    @if (auth()->user()->isAdmin() || (auth()->user()->isOrganizador() && auth()->user()->id == $evento->user_id))

                        <div class="div_gest_reserva">
                            <p><strong>Total de entradas del evento:</strong> {{$evento->num_entradas}}</p>

                            <form class="form_reserva" method="POST" action="{{ route('evento.bloquearReservas', $evento->id) }}">
                                @csrf
                                @method('PATCH')

                                @if($evento->reservas_habilitadas)
                                    <button type="submit" name="bloquear" value="1">Bloquear reservas</button>
                                @else
                                    <button type="submit" name="bloquear" value="0">Habilitar reservas</button>
                                @endif
                            </form>
                        </div>

                    @endif
                @endauth
                @guest
                    <p class='mensaje_error'><strong>Regístrate para poder reservar entradas</strong></p>
                @endguest
            @endif
        </div>

        @auth

            @if($entradasReservadas > 0)
                <div class="div_gest_evento tarjeta">
                <p class="mensaje_info"><strong>Tus entradas: {{ $entradasReservadas }}</strong></p>

                <div id="div_ver_reservas">
                    @include('partials.reservas-user-modal')
                    <a class='boton' href="#" id="linkReservasUser">Gestionar mis entradas</a>
                </div>

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

                @if($reservas->isNotEmpty())
                    <div id="div_ver_reservas">
                        @include('partials.reservas-modal')
                        <a class='boton' href="#" id="linkReservas">Ver y gestionar todas las reservas</a>
                    </div>
                @endif

                </div>

            @endif
        @endauth

    </div>
        
@endsection

