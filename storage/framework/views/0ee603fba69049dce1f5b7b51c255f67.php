  

<?php $__env->startSection('acceso_1'); ?>

    <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('zonaprivada')); ?>" class="boton">Área privada</a>
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

    <h2 class="letrero">Próximos eventos...</h2>
    
    <form class='form_filtro' method="get" action="<?php echo e(route('principal')); ?>">
        <!-- Select Ubicación -->
        <label for="ubicacion_id" class="sr-only">Ubicación</label>
        <select name="ubicacion_id" id="ubicacion_id">
            <option value="">Provincias (todas)</option>
            <?php $__currentLoopData = $ubicacionesGlobal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ubicacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ubicacion->id); ?>"
                    <?php echo e($ubicacionSeleccionada == $ubicacion->id ? 'selected' : ''); ?>>
                    <?php echo e($ubicacion->provincia); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <!-- Select Categoría -->
        <label for="categoria_id" class="sr-only">Categoría</label>
        <select name="categoria_id" id="categoria_id">
            <option value="">Categorías (todas)</option>
            <?php $__currentLoopData = $categoriasGlobal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($categoria->id); ?>"
                    <?php echo e($categoriaSeleccionada == $categoria->id ? 'selected' : ''); ?>>
                    <?php echo e($categoria->categoria); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>
    
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAdmin() || auth()->user()->isOrganizador()): ?>
            <div id="div_accesos_eventos_org">
                <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('principal', ['eventos_pasados' => 1])); ?>" class="boton">Todos eventos pasados</a>
                <?php else: ?> 
                    <a href="<?php echo e(route('principal', ['eventos_pasados' => 1])); ?>" class="boton">Ver mis eventos pasados</a>
                <?php endif; ?>
                <a href="<?php echo e(route('principal', ['mis_eventos' => 1])); ?>" class="boton">Ver mis eventos</a>
                <a href="<?php echo e(route('principal')); ?>" class="boton">Ver todos</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

    <?php if(session('success')): ?>
        <p class='mensaje_confir'><strong><?php echo e(session('success')); ?></strong></p>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
    <?php endif; ?>

    <!-- forelse sustituye a isset + foreach y la variable $eventosGlobal siempre esta presente para esta plantilla al utilizar un provider -->
    <?php $__empty_1 = true; $__currentLoopData = $eventosGlobal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div id="div_capsula" class="tarjeta">
            <a href="<?php echo e(route('mostrarevento', $evento->id)); ?>" class="capsula">
                <div class="div_superior_capsula">
                    <div class="nombre_evento_capsula">
                        <h3><?php echo e($evento->titulo); ?></h3>
                    </div>
                    <div>
                        <img class="imagen_link" src="<?php echo e(asset('imagenes/diseno/link.png')); ?>" alt="<?php echo e($evento->titulo); ?>">
                    </div>
                </div>

                <div class="div_info_capsula">
                    <div class="div_imagen_capsula">
                        <img class="imagen_evento" src="<?php echo e(asset('imagenes/eventos/' . $evento->cartel)); ?>" alt="<?php echo e($evento->titulo); ?>" fetchpriority=high>>
                    </div>
                
                    <div class="div_calendario_capsula">
                        <?php $__currentLoopData = $evento->mesesEvento; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Llamada al componente para generar el caledario visual -->
                            <?php if (isset($component)) { $__componentOriginal5c900092f420db512978e2351162465d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c900092f420db512978e2351162465d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.calendario','data' => ['mesData' => $mesData]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('calendario'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['mesData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mesData)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c900092f420db512978e2351162465d)): ?>
<?php $attributes = $__attributesOriginal5c900092f420db512978e2351162465d; ?>
<?php unset($__attributesOriginal5c900092f420db512978e2351162465d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c900092f420db512978e2351162465d)): ?>
<?php $component = $__componentOriginal5c900092f420db512978e2351162465d; ?>
<?php unset($__componentOriginal5c900092f420db512978e2351162465d); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <p class="informacion_capsula"><strong>Provincia:</strong> <?php echo e($evento->ubicacion->provincia); ?></p>
                        <p class="informacion_capsula"><strong>Tipo:</strong> <?php echo e($evento->categoria->categoria); ?></p>
                        <p class="informacion_capsula"><strong>Organiza:</strong> <?php echo e($evento->user->name); ?></p>
                    </div>
                                    
                </div>
            </a>

            <?php if(auth()->guard()->check()): ?>
                <?php if($evento->entradasReservadasPorUsuario(auth()->user()->id) > 0): ?>
                    <div class="div_gest_evento tarjeta">
                        <p class="mensaje_info"><strong>Tus entradas: <?php echo e($evento->entradasReservadasPorUsuario(auth()->user()->id)); ?></strong></p>
                     </div>
                <?php endif; ?>

                <?php if(auth()->user()->isAdmin() || (auth()->user()->isOrganizador() && auth()->user()->id == $evento->user_id)): ?>
                    <div class="div_gest_evento tarjeta">

                        <a href="<?php echo e(route('formupdateevento', $evento->id)); ?>" class="boton" >Editar evento</a>

                        <form method="POST" action="<?php echo e(route('evento.destroy', $evento->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            
                            <button type="submit" class="boton_riesgo" onclick="return confirm('¿Estás seguro de que quieres eliminar este evento?')">
                                Eliminar evento
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p>No hay eventos disponibles.</p>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\JR\Desktop\ruiz_bolivar_juanmanuel_PDAW_tarea3\mileventos\resources\views/navPrincipal.blade.php ENDPATH**/ ?>