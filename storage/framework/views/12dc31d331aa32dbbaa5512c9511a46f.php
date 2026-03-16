 

<?php $__env->startSection('acceso_1'); ?>
       
    <a href="<?php echo e(route('principal')); ?>" class="boton_inicio boton">Inicio</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_2'); ?> 
    
    <h2 class="letrero">Formulario de registro</h2>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

    <form class='tarjeta' method="POST" action="<?php echo e(route('registro.guardar')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <?php if($errors->any()): ?>
            <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
        <?php endif; ?>

        <div>
            <label for="name">Nombre completo</label>
            <input type="text" id="name" name="name"  value="<?php echo e(old('name')); ?>" placeholder="Introduce tu nombre" required>
        </div>
        <div>
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Introduce tu correo" required>
        </div>
        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Introduce tu contraseña" required>
        </div>
        <div>
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma tu contraseña" required>
        </div>
        <div>
            <label>Perfil de usuario</label>
            <select name="rol" required>
                <option value="" disabled selected hidden>Selecciona</option>
                <option value="2" <?php if(old('rol') == 2): echo 'selected'; endif; ?>>Organizador de eventos</option>
                <option value="3" <?php if(old('rol') == 3): echo 'selected'; endif; ?>>Usuario no organizador de eventos</option>
            </select>
        </div>
        <div>
            <label for="foto">Foto de perfil</label>
            <input type="file" id="foto" name="foto" accept="image/*">
        </div>
        <button type="submit">Registrar</button>
    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\area_de_desarrollo\repositorio_local\mileventos\resources\views/navFormRegistro.blade.php ENDPATH**/ ?>