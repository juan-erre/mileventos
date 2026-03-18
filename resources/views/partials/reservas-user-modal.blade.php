<!-- Modal para tabla de reservas de un usuario -->
<div id="modalReservasUser" class="modal">
    <div class="modal-contenido">

        <span class="cerrar">&times;</span>

        <h2>Reservas del evento</h2>

        <div class="modal-texto div_tabla_responsiva">

            <table class='tabla_base'>
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Fecha</th> 
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasUser as $reserva)
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

            <p><strong>Total reservas:</strong> {{ $reservasUser->count() }}</p>

        </div>

    </div>
</div>