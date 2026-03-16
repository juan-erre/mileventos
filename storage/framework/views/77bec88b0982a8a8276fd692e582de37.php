<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <!-- Título de la página-->
        <title>Mileventos</title>  
        <meta name="title" content="Mileventos">  
        <meta name="description" content="Mileventos">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <link rel="icon" type="image/png" sizes="76x76" href="<?php echo e(asset('imagenes/diseno/favicon_92x92.png')); ?>">   
        <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    </head>

    <body>

        <!-- Cabecera de la página web -->
        <header>
        
        </header>

        <!-- Zona de navegación dentro del encabezado -->
        <nav>
            <div class="accesos">

                <div class='div_accesos_izq'>
                    <?php echo $__env->yieldContent('acceso_1'); ?>
                </div>
                <?php if(auth()->guard()->check()): ?>
                    <p class='item1'> 👤 <strong><?php echo e(auth()->user()->name); ?></strong></p>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit">Cerrar sesión</button>
                    </form>
                <?php endif; ?>
                
                <?php if(auth()->guard()->guest()): ?>
                    <div></div>
                    <form id="form_login" class='item1' method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>                                                                           
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="email" required>

                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>

                        <button type="submit">Login</button>
                    </form>
                <?php endif; ?>
            </div> 

            <div class="accesos">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isAdmin() || auth()->user()->isOrganizador()): ?>
                        <div class='div_accesos_izq'>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <?php echo $__env->yieldContent('acceso_3'); ?>
                            <?php endif; ?>
                        </div>
                        <div></div> 
                        <?php echo $__env->yieldContent('acceso_4'); ?>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if(auth()->guard()->guest()): ?>
                    <div class='div_accesos_izq'></div>
                    <?php $__errorArgs = ['email', 'login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p id='nav_mensaje_error' class="mensaje_error"><strong><?php echo e($message); ?></strong></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div>
                        <?php echo $__env->yieldContent('acceso_2'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </nav>

        <!-- Zona de contenido principal -->
        <main>

        <h1 class="sr-only">Mileventos</h1>
        
            <!-- Subzona 1 -->
            <section id="contenido_1">

                <img id="cabecera_imagen" src="<?php echo e(asset('imagenes/diseno/cabecera.png')); ?>" alt="Imagen de cabecera">
            
            </section> 

            <!-- Subzona 2 -->
            <section id="contenido_2">

               <?php echo $__env->yieldContent('contenido_2'); ?>

            </section>
                
            <!-- Subzona 3 -->

            <section id="contenido_3">

                <?php echo $__env->yieldContent('contenido_3'); ?>
                
            </section>

        </main>
        
        <!-- Zona de pie de página -->
        <footer>

            <!-- Los documentos legales utilizan un modal -->
            <a href="#" id="linkPrivacidad">Política de privacidad</a>

            <div class="contacto item1">
                <p>Contacta con nosotros: <strong>contacto@mileventos.es</strong></p>
                <p>© Copyright mileventos.es <?php echo e(date('Y')); ?></p>
            </div>

            <a href="#" id="linkAviso">Aviso legal</a>

        </footer>

        <?php echo $__env->make('partials.privacy-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('partials.legal-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Script -->
        <script src="<?php echo e(asset('js/app.js')); ?>"></script>

    </body>
</html>








<?php /**PATH C:\area_de_desarrollo\repositorio_local\mileventos\resources\views/plantillas/paginaPrincipal.blade.php ENDPATH**/ ?>