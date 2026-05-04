<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">

        {{-- Marca --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-backpack2 me-2"></i>Horarios Escolares
        </a>

        {{-- Botón hamburguesa (responsive) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-house me-2"></i>Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.horarios') ? 'active' : '' }}"
                        href="{{ route('admin.horarios') }}">
                        <i class="bi bi-calendar-check me-2"></i>Horarios
                    </a>
                </li>
                @can('abm-cursos')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cursos') || request()->routeIs('admin.cursos.*') ? 'active' : '' }}"
                            href="{{ route('admin.cursos.listado') }}">
                            <i class="bi bi-mortarboard me-2"></i>Cursos
                        </a>
                    </li>
                @endcan
                @can('abm-docentes')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.docentes') ? 'active' : '' }}"
                            href="{{ route('admin.docentes') }}">
                            <i class="bi bi-person-badge me-2"></i>Docentes
                        </a>
                    </li>
                @endcan
                @can('abm-espacios')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.espacios') || request()->routeIs('admin.espacios.*') ? 'active' : '' }}"
                            href="{{ route('admin.espacios.utilizacion') }}">
                            <i class="bi bi-building me-2"></i>Espacios
                        </a>
                    </li>
                @endcan
                @can('ver-cambios-horario')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.cambios-horario') ? 'active' : '' }}"
                            href="{{ route('admin.cambios-horario') }}">
                            <i class="bi bi-repeat me-2"></i>Cambios
                        </a>
                    </li>
                @endcan
                @if (auth()->user()?->isSuperAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.instituciones') || request()->routeIs('admin.materias') || request()->routeIs('admin.usuarios') ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-gear me-2"></i>Admin escuelas
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.instituciones') }}">
                                    <i class="bi bi-plus me-2"></i>Administración escuelas
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.materias') }}">
                                    <i class="bi bi-plus me-2"></i>Administración materias
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.usuarios') }}">
                                    <i class="bi bi-people me-2"></i>Administración usuarios
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                @endif
            </ul>

            {{-- Espaciador para empujar a la derecha --}}
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @if (auth()->check())
                    @php
                        $usuarioActual = auth()->user();
                        $institucionActiva = $usuarioActual?->institucionActiva;
                        $rolActivo = $usuarioActual?->roleNameInInstitucion($institucionActiva?->id);
                        $rolesActivos = [
                            'admin' => 'Administrador',
                            'preceptor' => 'Preceptor',
                            'aprobador' => 'Aprobador',
                            'secretario' => 'Secretario',
                            'solicitante' => 'Solicitante',
                            'Super-admin' => 'Super-admin',
                        ];
                    @endphp
                    <li class="nav-item me-lg-3">
                        <span class="navbar-text small text-white-50">
                            @if ($institucionActiva)
                                {{ $institucionActiva->nombre_institucion }}@if($rolActivo), {{ $rolesActivos[$rolActivo] ?? ucfirst($rolActivo) }}@endif
                            @else
                                Sin escuela activa
                            @endif
                        </span>
                    </li>
                @endif

                {{-- Usuario Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        {{ auth()->user()->name ?? 'Usuario' }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text small text-muted">
                                {{ auth()->user()->email ?? '' }}
                            </span>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

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

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @if ((auth()->user()?->institucionesDisponibles->count() ?? 0) > 1)
                            <li>
                                <a class="dropdown-item" href="{{ route('instituciones.select') }}">
                                    Cambiar escuela
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif

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
