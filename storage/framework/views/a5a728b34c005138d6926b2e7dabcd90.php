  

<?php $__env->startSection('acceso_1'); ?>

    <a href="<?php echo e(route('principal')); ?>" class="boton_inicio boton">Inicio</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_3'); ?>

    <a href="<?php echo e(route('gestionusuarios')); ?>" class="boton">Gestión de usuarios</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_4'); ?>

    <a href="<?php echo e(route('formregistroevento')); ?>" class="boton" >Registrar evento</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_2'); ?> 

    <h2 class="letrero">Gestión de usuarios</h2>

    <?php if(!isset ($userEdit)): ?>
         <form class='form_filtro' method="get" action="<?php echo e(route('gestionusuarios')); ?>">
            <!-- Select usuario -->
            <select name="rol_id">
                <option value="">Todos los usuarios</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($rol->id); ?>"
                        <?php echo e($rolSeleccionado == $rol->id ? 'selected' : ''); ?>>
                        <?php echo e($rol->rol); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit">Filtrar</button>
        </form>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

    <?php if(session('success')): ?>
        <p class='mensaje_confir'><strong><?php echo e(session('success')); ?></strong></p>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
    <?php endif; ?>

    <?php if(!isset ($userEdit)): ?>
        <div id="div_tabla_usuarios">
            <table id='tabla_usuarios' class="tabla_base">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $usuariosFiltrados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href='<?php echo e(route('verusuario', $user)); ?>' ><?php echo e($user->name); ?></a></td>
                            <td><a href='<?php echo e(route('verusuario', $user)); ?>' ><?php echo e($user->email); ?></a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3">No hay usuarios</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <!-- Se accede a la ficha del usuario -->
        <h2 class='letrero_importante' ><?php echo e($userEdit->name); ?></h2>

        <div class='secciones_datos_usuario'> 
            <div class="ficha_user tarjeta">
                <div>
                    <h3><?php echo e($userEdit->name); ?></h3>
                    <h3><?php echo e($userEdit->email); ?></h3>
                    <h3><?php echo e($userEdit->rol->rol); ?></h3>
                </div>
                <div id='div_img_perfil'>
                <?php if($userEdit->foto): ?>
                    <img id="img_perfil" src="<?php echo e(asset('imagenes/usuarios/' . $userEdit->foto)); ?>" alt="Foto de <?php echo e($userEdit->name); ?>">
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

        <div class='secciones_datos_usuario'>
            <h2 class='letrero' >Editar datos del usuario</h2>
            <form class="tarjeta" method="POST" action="<?php echo e(route('usuario.update')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <input type="hidden" name="user_id" value="<?php echo e($userEdit->id); ?>">
                <div>
                    <label for="name">Nombre completo</label>
                    <input type="text" name="name" value="<?php echo e($userEdit->name); ?>">
                </div>
                <div>
                    <label for="email">Correo electrónico</label>
                    <input type="email" name="email" value="<?php echo e($userEdit->email); ?>">
                </div>
                <div>
                    <label for="password">Nueva contraseña</label>
                    <input type="password" name="password" placeholder="Introduce tu contraseña">
                </div>
                <div>
                    <label  for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" placeholder="Confirma tu contraseña">
                </div>
                <div>
                    <label>Perfil de usuario</label>
                    <?php if($userEdit->rol->id == 2): ?>
                        <input type="text" value="<?php echo e($userEdit->rol_id == 2 ? 'Organizador de eventos' : ''); ?>" disabled>
                    <?php else: ?>
                        <select name="rol">
                            <option value="" disabled selected hidden>Selecciona</option>
                            <option value="2" <?php if($userEdit->rol_id == 2): echo 'selected'; endif; ?>>Organizador de eventos</option>
                            <option value="3" <?php if($userEdit->rol_id == 3): echo 'selected'; endif; ?>>Usuario no organizador de eventos</option>
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

        <!-- Se confirma con JS antes de intentar eliminar -->
        <div class="secciones_datos_usuario">
            <h2 class="letrero">Dar de baja al usuario</h2>

            <form class="form_eliminar tarjeta" method="POST" action="<?php echo e(route('usuario.destroy')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <p>Escribe <strong>ELIMINAR</strong> para confirmar:</p>

                <input type="hidden" name="user_id" value="<?php echo e($userEdit->id); ?>">
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
<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\area_de_desarrollo\repositorio_local\mileventos\resources\views/navGestionUsuarios.blade.php ENDPATH**/ ?>