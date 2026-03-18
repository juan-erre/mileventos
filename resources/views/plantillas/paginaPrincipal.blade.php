<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <!-- Título de la página-->
        <title>Mileventos</title>  
        <meta name="title" content="Mileventos">  
        <meta name="description" content="Mileventos">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <link rel="icon" type="image/png" sizes="76x76" href="{{ asset('imagenes/diseno/favicon_92x92.png') }}">   
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>

    <body>

        <!-- Cabecera de la página web -->
        <header>
        
        </header>

        <!-- Zona de navegación dentro del encabezado -->
        <nav>
            <div class="accesos">

                <div class='div_accesos_izq'>
                    @yield('acceso_1')
                </div>
                @auth
                    <p class='item1'> 👤 <strong>{{ auth()->user()->name}}</strong></p>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                @endauth
                
                @guest
                    <div></div>
                    <form id="form_login" class='item1' method="POST" action="{{ route('login') }}">
                        @csrf                                                                           
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="email" required>

                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>

                        <button type="submit">Login</button>
                    </form>
                @endguest
            </div> 

            <div class="accesos">
                @auth
                    @if (auth()->user()->isAdmin() || auth()->user()->isOrganizador())
                        <div class='div_accesos_izq'>
                            @if (auth()->user()->isAdmin())
                                @yield('acceso_3')
                            @endif
                        </div>
                        <div></div> 
                        @yield('acceso_4')
                    @endif

                @endauth

                @guest
                    <div class='div_accesos_izq'></div>
                    @error('email', 'login')
                        <p id='nav_mensaje_error' class="mensaje_error"><strong>{{ $message }}</strong></p>
                    @enderror
                    <div>
                        @yield('acceso_2')
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Zona de contenido principal -->
        <main>

        <h1 class="sr-only">Mileventos</h1>
        
            <!-- Subzona 1 -->
            <section id="contenido_1">

                <img id="cabecera_imagen" src="{{ asset('imagenes/diseno/cabecera.png') }}" alt="Imagen de cabecera">
            
            </section> 

            <!-- Subzona 2 -->
            <section id="contenido_2">

               @yield('contenido_2')

            </section>
                
            <!-- Subzona 3 -->

            <section id="contenido_3">

                @yield('contenido_3')
                
            </section>

        </main>
        
        <!-- Zona de pie de página -->
        <footer>

            <!-- Los documentos legales utilizan un modal -->
            <a href="#" id="linkPrivacidad">Política de privacidad</a>

            <div class="contacto item1">
                <p>Contacta con nosotros: <strong>contacto@mileventos.es</strong></p>
                <p>© Copyright mileventos.es {{ date('Y') }}</p>
            </div>

            <a href="#" id="linkAviso">Aviso legal</a>

        </footer>

        @include('partials.privacy-modal')
        @include('partials.legal-modal')

        <!-- Script -->
        <script src="{{asset('js/app.js')}}"></script>

    </body>
</html>








