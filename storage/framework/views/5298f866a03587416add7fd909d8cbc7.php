<!-- componente que genera el calendario para los eventos -->
<?php
    $anio = $mesData['anio'];
    $mes = $mesData['mes'];
    $diasEvento = $mesData['diasEvento'];

    $inicioMes = \Carbon\Carbon::create($anio, $mes, 1);
    $diasEnMes = $inicioMes->daysInMonth;
    $primerDiaSemana = $inicioMes->dayOfWeekIso;
    $dia = 1;
?>

<table id="tabla_reservas" class="tabla_base">
    <caption class="mes"><strong><?php echo e($inicioMes->locale('es')->translatedFormat('F Y')); ?></strong></caption>
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
        <?php for($semana = 0; $semana < 6; $semana++): ?>
            <tr>
                <?php for($i = 1; $i < 8; $i++): ?>
                    <?php if($semana == 0 && $i < $primerDiaSemana): ?>
                        <td></td>
                    <?php elseif($dia > $diasEnMes): ?>
                        <td></td>
                    <?php else: ?>
                        <td class="<?php echo e(in_array($dia, $diasEvento) ? 'dias_evento' : ''); ?>">
                            <?php echo e($dia); ?>

                            <?php $dia++; ?>
                        </td>
                    <?php endif; ?>
                <?php endfor; ?>
            </tr>
            <?php if($dia > $diasEnMes): ?>
                <?php break; ?>
            <?php endif; ?>
        <?php endfor; ?>
    </tbody>
</table>
                                                                        <?php /**PATH C:\area_de_desarrollo\repositorio_local\otros_proyectos_y_plantillas\mileventos\resources\views/components/calendario.blade.php ENDPATH**/ ?>