  

<?php $__env->startSection('acceso_1'); ?>

    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('principal')); ?>" class="boton_inicio boton">Inicio</a>
    <?php endif; ?>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_2'); ?>

    <a href="<?php echo e(route('formregistro')); ?>" class="boton">Regístrate</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_3'); ?>

    <a href="<?php echo e(route('gestionusuarios')); ?>" class="boton">Gestión de usuarios</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_4'); ?>

    <a href="<?php echo e(route('formregistroevento')); ?>" class="boton" >Registrar evento</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_2'); ?> 
    
    <h2 class="letrero">Edita tu evento</h2>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

      <form class='tarjeta' method="POST" action="<?php echo e(route('evento.update', $evento->id)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php if(session('success')): ?>
            <p class='mensaje_confir'><strong><?php echo e(session('success')); ?></strong></p>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
        <?php endif; ?>
        <?php if(isset($mensaje) && $evento->entradas()->whereHas('reserva')->exists()): ?>
            <p class="mensaje_error"><strong><?php echo e($mensaje); ?></strong></p>
        <?php endif; ?>

        <div>
            <label for="name">Título</label>
            <input type="text" id="titulo" name="titulo"  value="<?php echo e($evento->titulo); ?>">
        </div>

        <!-- Select Ubicación -->
        <label for="ubicacion_id">Localización</label>
        <select name="ubicacion_id">
            <option value="" disabled hidden>Provincia</option>             
            <?php $__currentLoopData = $ubicacionesGlobal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ubicacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ubicacion->id); ?>"
                    <?php echo e($evento->ubicacion->id == $ubicacion->id ? 'selected' : ''); ?>>
                    <?php echo e($ubicacion->provincia); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <!-- Select Categoría -->
        <label for="categoria_id">Tipo de evento</label>
        <select name="categoria_id">
            <option value="" disabled hidden>Categorías</option>
            <?php $__currentLoopData = $categoriasGlobal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($categoria->id); ?>"
                    <?php echo e($evento->categoria->id == $categoria->id ? 'selected' : ''); ?>>
                    <?php echo e($categoria->categoria); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

         <!-- Fechas -->
        <div>
            <label for="fecha_inicio">Fecha inicio</label>
            <input type="date" name="fecha_inicio" value="<?php echo e($evento->fecha_inicio->format('Y-m-d')); ?>">
        </div>

        <div>
            <label for="fecha_fin">Fecha fin</label>
            <input type="date" name="fecha_fin" value="<?php echo e($evento->fecha_fin->format('Y-m-d')); ?>">
        </div>

        <!-- Descripción -->
        <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" rows="4" placeholder="Describe el evento"><?php echo e($evento->descripcion); ?></textarea>
        </div>

        <!-- Número de entradas -->
        <div>
            <label for="num_entradas">Número de entradas</label>
            <input type="number" name="num_entradas" min="0" value="<?php echo e($evento->num_entradas); ?>">
        </div>

        <div>
            <label for="cartel">Foto de portada</label>
            <input type="file" id="cartel" name="cartel" accept="image/*">
        </div>
        <button type="submit">Guadar cambios</button>
    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\area_de_desarrollo\repositorio_local\mileventos\resources\views/navFormUpdateEvento.blade.php ENDPATH**/ ?>