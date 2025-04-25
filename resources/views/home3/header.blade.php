<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="{{ url('/') }}" class="logo">
                        <img src="assets/images/logo.png" alt="">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li>
                            <a href="{{ url('index') }}" class="btn btn-red-gradient">
                                <i class="fas fa-home"></i>Menu
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('explore') }}" class="btn btn-red-gradient">
                                <i class="fas fa-search"></i>Explore
                            </a>

                        </li>

                        <!-- Si el usuario está autenticado -->
                        @if (Route::has('login'))
                            @auth
                                <li>
                                    <a href="{{ url('book_history') }}" class="btn btn-red-gradient">
                                        <i class="fas fa-history"></i>Mi Historial
                                    </a>
                                </li>

                                <!-- Dropdown de Perfil -->
                                <li class="dropdown">
                                    <a
                                        href="#"
                                        class="btn btn-red-gradient dropdown-toggle"
                                        id="profileDropdown"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-user"></i> Perfil
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('perfil.show') }}">Administrar Perfil</a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button class="dropdown-item" type="submit">Cerrar Sesión</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Incluir el x-app-layout -->

                            @endauth
                        @endif
                    </ul>
                    <a class="menu-trigger">
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>

