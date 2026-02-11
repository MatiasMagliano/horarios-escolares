<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">

        {{-- Marca --}}
        <a class="navbar-brand" href="#">
            Horarios Escolares
        </a>

        {{-- Botón hamburguesa (responsive) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Espaciador para empujar a la derecha --}}
            <ul class="navbar-nav ms-auto align-items-lg-center">

                {{-- Usuario Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       href="#"
                       id="userDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        {{ auth()->user()->name ?? 'Usuario' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text small text-muted">
                                {{ auth()->user()->email ?? '' }}
                            </span>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Perfil
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Configuración
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
