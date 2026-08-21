{{--
    Capa VISTA - Plantilla maestra del panel administrativo.

    El menu lateral se genera a partir del catalogo de casos de uso
    (config/casos_uso.php), de modo que cada opcion muestra el sprint al que
    pertenece y el NOMBRE DEL CASO DE USO que ejecuta, con la trazabilidad
    CU <-> HU <-> RF tomada del documento de Analisis y Diseno.
--}}
@use(App\Modelo\CatalogoCasosUso)
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Panel') · SISARST</title>
    @vite(['app/Vista/recursos/scss/app.scss', 'app/Vista/recursos/js/app.js'])
</head>
<body>
<div class="sisarst-layout">

    <aside class="sisarst-sidebar no-imprimir">
        <div class="sisarst-brand">
            <strong>SISARST</strong>
            <small>Red de Salud Tayacaja</small>
        </div>

        <nav class="nav flex-column mt-2">

            {{-- ── Dashboard ───────────────────────────────────────────── --}}
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            {{-- ── Un grupo por sprint, con sus casos de uso ────────────── --}}
            @foreach (CatalogoCasosUso::sprints() as $sprint)
                @php
                    $n           = (int) $sprint['numero'];
                    $casosSprint = CatalogoCasosUso::delSprint($n);
                    $opciones    = CatalogoCasosUso::menuDelSprint($n);
                    $rutasSprint = $casosSprint->pluck('rutas')->flatten()->all();
                    $abierto     = collect($rutasSprint)->contains(fn ($r) => request()->routeIs($r));
                @endphp

                @if ($opciones->isNotEmpty())
                    <button class="nav-link sisarst-nav-toggle {{ $abierto ? 'active' : '' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#nav-s{{ $n }}"
                            title="{{ $sprint['nombre'] }}"
                            aria-expanded="{{ $abierto ? 'true' : 'false' }}">
                        <i class="bi {{ $sprint['icono'] }}"></i>
                        <span class="sisarst-nav-grupo">
                            <span class="sisarst-nav-modulo">{{ $sprint['nombre'] }}</span>
                        </span>
                        <i class="bi bi-chevron-down sisarst-chevron ms-auto"></i>
                    </button>

                    <div class="collapse {{ $abierto ? 'show' : '' }}" id="nav-s{{ $n }}">
                        <div class="sisarst-submenu">
                            @foreach ($opciones as $caso)
                                @php($activo = collect($caso['rutas'])->contains(fn ($r) => request()->routeIs($r)))
                                <a class="nav-link sisarst-cu {{ $activo ? 'active' : '' }}"
                                   href="{{ route($caso['ruta_menu']) }}"
                                   title="{{ $caso['descripcion'] }}">
                                    <i class="bi {{ $caso['icono'] }}"></i>
                                    <span class="sisarst-cu-texto">
                                        <span class="sisarst-cu-nombre">{{ $caso['nombre'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

        </nav>

    </aside>

    <div class="sisarst-main">

        <header class="sisarst-topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none js-toggle-menu" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    {{-- Rotulo del caso de uso que implementa esta pantalla.
                         Se resuelve automaticamente desde la ruta activa. --}}
                    @isset($casoUso)
                        @if ($casoUso)
                            <div class="sisarst-cu-badge"
                                 title="{{ $casoUso['descripcion'] }} · Actor: {{ $casoUso['actor'] }}">
                                <span class="sisarst-cu-badge-sprint">
                                    {{ $sprintUso['nombre'] ?? '' }}
                                </span>
                                <span class="sisarst-cu-badge-cu">
                                    {{ $casoUso['nombre'] }}
                                </span>
                            </div>
                        @endif
                    @endisset
                    <h1 class="h5 mb-0">@yield('titulo', 'Panel')</h1>
                    <small class="text-muted">@yield('subtitulo')</small>
                </div>
            </div>

            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="dropdown" type="button">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="text-start lh-sm d-none d-sm-block">
                        <span class="d-block small fw-semibold">{{ auth()->user()->nombre_mostrado }}</span>
                        <span class="d-block text-muted" style="font-size:.72rem">
                            {{ auth()->user()->roles->pluck('nombre')->implode(' · ') ?: 'Sin rol asignado' }}
                        </span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header small">{{ auth()->user()->correo_institucional }}</li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="sisarst-content">
            @include('partials.mensajes')
            @yield('contenido')
        </main>
    </div>
</div>

{{-- Scripts inyectados por las vistas hijas (validaciones, pickers, etc.) --}}
@stack('scripts')
</body>
</html>
