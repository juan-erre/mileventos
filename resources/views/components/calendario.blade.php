<!-- componente que genera el calendario para los eventos -->
@php
    $anio = $mesData['anio'];
    $mes = $mesData['mes'];
    $diasEvento = $mesData['diasEvento'];

    $inicioMes = \Carbon\Carbon::create($anio, $mes, 1);
    $diasEnMes = $inicioMes->daysInMonth;
    $primerDiaSemana = $inicioMes->dayOfWeekIso;
    $dia = 1;
@endphp

<table id="tabla_reservas" class="tabla_base">
    <caption class="mes"><strong>{{ $inicioMes->locale('es')->translatedFormat('F Y') }}</strong></caption>
    <thead>
        <tr>
            <th>L</th>
            <th>M</th>
            <th>X</th>
            <th>J</th>
            <th>V</th>
            <th>S</th>
            <th>D</th>
        </tr>
    </thead>
    <tbody>
        @for ($semana = 0; $semana < 6; $semana++)
            <tr>
                @for ($i = 1; $i < 8; $i++)
                    @if ($semana == 0 && $i < $primerDiaSemana)
                        <td></td>
                    @elseif ($dia > $diasEnMes)
                        <td></td>
                    @else
                        <td class="{{ in_array($dia, $diasEvento) ? 'dias_evento' : '' }}">
                            {{ $dia }}
                            @php $dia++; @endphp
                        </td>
                    @endif
                @endfor
            </tr>
            @if ($dia > $diasEnMes)
                @break
            @endif
        @endfor
    </tbody>
</table>
                                                                        