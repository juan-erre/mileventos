  

<?php $__env->startSection('acceso_1'); ?>

    <a href="<?php echo e(route('principal')); ?>" class="boton_inicio boton">Inicio</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_3'); ?>

    <a href="<?php echo e(route('gestionusuarios')); ?>" class="boton">Gestión de usuarios</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_2'); ?> 

    <h2 class="letrero">Datos personales</h2>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_4'); ?>

    <a href="<?php echo e(route('formregistroevento')); ?>" class="boton" >Registrar evento</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

    <div class='secciones_datos_usuario'> 
        <div class='ficha_user tarjeta'>
            <div>
                <h3><?php echo e(auth()->user()->name); ?></h3>
                <h3><?php echo e(auth()->user()->email); ?></h3>
                <?php if(auth()->user()->isAdmin() || auth()->user()->isOrganizador()): ?>
                    <h3><?php echo e(auth()->user()->rol->rol); ?></h3>
                <?php endif; ?>
            </div>
            <div id='div_img_perfil'>
            <?php if(auth()->user()->foto): ?>
                <img id="img_perfil" src="<?php echo e(asset('imagenes/usuarios/' . auth()->user()->foto)); ?>" alt="Foto de <?php echo e(auth()->user()->name); ?>" fetchpriority=high>>
            <?php else: ?>
                Sin foto
            <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <p class='mensaje_confir'><strong><?php echo e(session('success')); ?></strong></p>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
    <?php endif; ?>

    <?php if($reservas->isNotEmpty()): ?>
        <h2 class='letrero' >Mis entradas reservas</h2>
        <div class='secciones_datos_usuario div_tabla_responsiva'>
            <table class='tabla_base'>
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reservas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reserva): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
        </div>
    <?php endif; ?>
    <div class='secciones_datos_usuario'>
        <h2 class='letrero' >Editar mis datos</h2>
        <form class='tarjeta' method="POST" action="<?php echo e(route('usuario.update')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="name">Nombre completo</label>
                <?php if(auth()->user()->isAdmin()): ?>
                    <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" disabled>
                <?php else: ?>
                    <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>">
                <?php endif; ?>
            </div>
            <div>
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" disabled>
            </div>
            <div>
                <label for="password">Nueva contraseña (opcional)</label>
                <input type="password" name="password" placeholder="Introduce tu contraseña">
            </div>
            <div>
                <label  for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" placeholder="Confirma tu contraseña">
            </div>
            <div>
                <label>Perfil de usuario</label>
                <?php if(auth()->user()->isAdmin() || auth()->user()->isOrganizador()): ?>
                    <input type="text" value="<?php echo e(auth()->user()->rol_id == 2 ? 'Organizador de eventos' : 'Administrador'); ?>" disabled>
                <?php else: ?>
                    <select name="rol">
                        <option value="" disabled selected hidden>Selecciona</option>
                        <option value="2" <?php if(auth()->user()->rol_id == 2): echo 'selected'; endif; ?>>Organizador de eventos</option>
                        <option value="3" <?php if(auth()->user()->rol_id == 3): echo 'selected'; endif; ?>>Usuario no organizador de eventos</option>
                    </select>
                <?php endif; ?>
            </div>
            <div>
                <label for="foto">Foto de perfil</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>    

            <button type="submit">Guardar cambios</button>
        </form>
    </div>

    <?php if(auth()->user()->isAdmin()): ?>

    <?php else: ?>
    <!-- Se confirma con JS antes de intentar eliminar -->
    <div class="secciones_datos_usuario">
        <h2 class="letrero">Dar de baja mi cuenta</h2>

        <form class="form_eliminar tarjeta" method="POST" action="<?php echo e(route('usuario.destroy')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <p>Escribe <strong>ELIMINAR</strong> para confirmar:</p>

            <input type="text" name="confirmacion" id="confirmacion" placeholder="Escribe ELIMINAR">
            <p id="errorConfirmacion" style="color:red; display:none;"><strong> Escribir ELIMINAR para confirmar.</strong></p>

            <?php if(session('error')): ?>
                <p class="mensaje_error"><strong><?php echo e(session('error')); ?></strong></p>
            <?php endif; ?>

            <button type="submit" id="btnEliminar" class="boton_riesgo" disabled>Eliminar cuenta</button>
        </form>
    </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\JR\Desktop\ruiz_bolivar_juanmanuel_PDAW_tarea3\mileventos\resources\views/navPrivada.blade.php ENDPATH**/ ?>