  

<?php $__env->startSection('acceso_1'); ?>

    <a href="<?php echo e(route('principal')); ?>" class="boton_inicio boton">Inicio</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_3'); ?>

    <a href="<?php echo e(route('gestionusuarios')); ?>" class="boton">Gestión de usuarios</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_2'); ?> 

    <h2 class="letrero_importante"><?php echo e($evento->titulo); ?></h2>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('acceso_4'); ?>

    <a href="<?php echo e(route('formregistroevento')); ?>" class="boton" >Registrar evento</a>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contenido_3'); ?> 

    <?php if(auth()->guard()->check()): ?>

        <?php if(session('success')): ?>
            <p class='mensaje_confir'><strong><?php echo e(session('success')); ?></strong></p>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <p class='mensaje_error'><strong><?php echo e($errors->first()); ?></strong></p>
        <?php endif; ?>
        <?php if(!session('success') && !$errors->any()): ?>
            <p class="mensaje_error"><strong><?php echo e(session('mensaje_reservas')); ?></strong></p>
        <?php endif; ?>

    <?php endif; ?>

    <div id='div_principal_evento' class='tarjeta'>
        <div id="div_imagen_evento">
            <img class="imagen_evento" src="<?php echo e(asset('imagenes/eventos/' . $evento->cartel)); ?>" alt="<?php echo e($evento->titulo); ?>">
        </div>

        <div id="div_calendario_evento">
            <div>
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
            </div>
            <div>
                <p><strong>Provincia:</strong> <?php echo e($evento->ubicacion->provincia); ?></p>
                <p><strong>Tipo de evento:</strong> <?php echo e($evento->categoria->categoria); ?></p>
                <p><strong>Organiza:</strong> <?php echo e($evento->user->name); ?></p>
            </div>
        </div>
        <div id="div_masinfo_evento">
            <p><strong>Más información:</strong> <?php echo e($evento->descripcion); ?></p>
            <?php if($evento->num_entradas > 0): ?>
                <div class="div_gest_reserva">
                    <p><strong>Entradas disponibles:</strong> <?php echo e($evento->entradasLibres()); ?></p>
                    <?php if(auth()->guard()->check()): ?>
                    <form class="form_reserva" method="POST" action="<?php echo e(route('reservar', $evento->id)); ?>">
                        <?php echo csrf_field(); ?>

                        <input type="number" name="cantidad" min="1" value="1" required>
                        
                        <button type="submit">Reservar</button>
                      </form>
                    <?php endif; ?>
                </div>
                                
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAdmin() || (auth()->user()->isOrganizador() && auth()->user()->id == $evento->user_id)): ?>

                        <div class="div_gest_reserva">
                            <p><strong>Total de entradas del evento:</strong> <?php echo e($evento->num_entradas); ?></p>

                            <form class="form_reserva" method="POST" action="<?php echo e(route('evento.bloquearReservas', $evento->id)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>

                                <?php if($evento->reservas_habilitadas): ?>
                                    <button type="submit" name="bloquear" value="1">Bloquear reservas</button>
                                <?php else: ?>
                                    <button type="submit" name="bloquear" value="0">Habilitar reservas</button>
                                <?php endif; ?>
                            </form>
                        </div>

                    <?php endif; ?>
                <?php endif; ?>
                <?php if(auth()->guard()->guest()): ?>
                    <p class='mensaje_error'><strong>Regístrate para poder reservar entradas</strong></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if(auth()->guard()->check()): ?>

            <?php if($entradasReservadas > 0): ?>
                <div class="div_gest_evento tarjeta">
                <p class="mensaje_info"><strong>Tus entradas: <?php echo e($entradasReservadas); ?></strong></p>

                <div id="div_ver_reservas">
                    <?php echo $__env->make('partials.reservas-user-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <a class='boton' href="#" id="linkReservasUser">Gestionar mis entradas</a>
                </div>

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

                <?php if($reservas->isNotEmpty()): ?>
                    <div id="div_ver_reservas">
                        <?php echo $__env->make('partials.reservas-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <a class='boton' href="#" id="linkReservas">Ver y gestionar todas las reservas</a>
                    </div>
                <?php endif; ?>

                </div>

            <?php endif; ?>
        <?php endif; ?>

    </div>
        
<?php $__env->stopSection(); ?>


<?php echo $__env->make('plantillas.paginaPrincipal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\JR\Desktop\ruiz_bolivar_juanmanuel_PDAW_tarea3\mileventos\resources\views/navEvento.blade.php ENDPATH**/ ?>