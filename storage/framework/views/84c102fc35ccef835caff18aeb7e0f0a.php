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
                    <?php $__empty_1 = true; $__currentLoopData = $reservasUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($reserva->entrada->evento->titulo); ?></td>
                            <td><?php echo e($reserva->entrada->evento->fecha_inicio->format('d/m/Y')); ?></td>
                            <td>
                                <form method="POST" action="<?php echo e(route('reserva.cancelar', $reserva->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="boton_riesgo" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4">No tienes reservas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <p><strong>Total reservas:</strong> <?php echo e($reservasUser->count()); ?></p>

        </div>

    </div>
</div><?php /**PATH C:\area_de_desarrollo\repositorio_local\mileventos\resources\views/partials/reservas-user-modal.blade.php ENDPATH**/ ?>