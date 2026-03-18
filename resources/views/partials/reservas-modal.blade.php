<!-- Modal para tabla de reservas -->
<div id="modalReservas" class="modal">
    <div class="modal-contenido">

        <span class="cerrar">&times;</span>

        <h2>Reservas del evento</h2>

        <div class="modal-texto div_tabla_responsiva">

            <table class='tabla_base'>
                <thead>
                    <tr>
                        <th>ID Entrada</th>
                        <th>Usuario</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reservas as $reserva)
                     <tr>
                            <td>{{ $reserva->entrada->id }}</td>
                            <td>{{ $reserva->user->name }}</td>
                            <td>
                                <form method="POST" action="{{ route('reserva.cancelar', $reserva->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="boton_riesgo" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay reservas</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            <p><strong>Total reservas:</strong> {{ $reservas->count() }}</p>

        </div>

    </div>
</div>