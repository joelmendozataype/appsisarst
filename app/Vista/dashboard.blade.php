{{-- Capa VISTA - Tablero profesional (Sprints 1-5). --}}
@extends('layouts.app')

@section('titulo', 'Dashboard')
@section('subtitulo', 'Panel de control · Red de Salud Tayacaja')

@push('scripts')
<style>
/* ── Hero ───────────────────────────────────────────────────────────── */
.db-hero {
    background: linear-gradient(135deg, #0b2d4d 0%, #0d5aa7 60%, #1976d2 100%);
    border-radius: .75rem;
    color: #fff;
    padding: 1.75rem 2rem;
    margin-bottom: 1.75rem;
    position: relative;
    overflow: hidden;
}
.db-hero::after {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.db-hero::before {
    content: '';
    position: absolute;
    right: 60px; bottom: -60px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.db-hero-title { font-size: 1.35rem; font-weight: 700; margin-bottom: .2rem; }
.db-hero-sub   { font-size: .88rem; opacity: .8; }
.db-hero-badge {
    background: rgba(255,255,255,.15);
    border-radius: 2rem;
    padding: .3rem .9rem;
    font-size: .78rem;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.2);
}
.db-hero-stat {
    text-align: center;
    min-width: 90px;
}
.db-hero-stat .num { font-size: 2rem; font-weight: 800; line-height: 1; }
.db-hero-stat .lbl { font-size: .7rem; opacity: .75; text-transform: uppercase; letter-spacing: .06em; }

/* ── Sección header ─────────────────────────────────────────────────── */
.db-section {
    display: flex;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1rem;
    margin-top: 1.5rem;
}
.db-section-icon {
    width: 32px; height: 32px;
    border-radius: .45rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.db-section h2 { font-size: .95rem; font-weight: 700; margin: 0; }
.db-section hr  { flex: 1; margin: 0; border-color: #e9ecef; }
.db-section a   { font-size: .8rem; white-space: nowrap; }

/* ── KPI cards mejoradas ────────────────────────────────────────────── */
.kpi-pro {
    border: 0;
    border-radius: .65rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04);
    transition: box-shadow .18s;
}
.kpi-pro:hover { box-shadow: 0 4px 14px rgba(0,0,0,.13); }
.kpi-pro .kpi-icon {
    width: 48px; height: 48px;
    border-radius: .55rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.kpi-pro .kpi-num  { font-size: 2rem; font-weight: 800; line-height: 1; color: #1a1a2e; }
.kpi-pro .kpi-lbl  { font-size: .73rem; text-transform: uppercase; letter-spacing: .07em; color: #6c757d; margin-top: .2rem; }
.kpi-pro .kpi-pill {
    font-size: .68rem;
    border-radius: 2rem;
    padding: .15rem .55rem;
    font-weight: 600;
}

/* Variantes de color icon */
.icon-blue   { background: #dbeafe; color: #1d4ed8; }
.icon-green  { background: #dcfce7; color: #15803d; }
.icon-red    { background: #fee2e2; color: #b91c1c; }
.icon-yellow { background: #fef9c3; color: #92400e; }
.icon-purple { background: #ede9fe; color: #6d28d9; }
.icon-cyan   { background: #cffafe; color: #0e7490; }

/* ── Progress asistencia ────────────────────────────────────────────── */
.asis-bar {
    border-radius: .65rem;
    border: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
}
.asis-bar .progress {
    height: 8px;
    border-radius: 4px;
    background: #f1f5f9;
}
.asis-bar .stat-row {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .55rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.asis-bar .stat-row:last-child { border-bottom: 0; }
.asis-bar .stat-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.asis-bar .stat-label { font-size: .82rem; flex: 1; }
.asis-bar .stat-count { font-size: .88rem; font-weight: 700; min-width: 28px; text-align: right; }

/* ── Tabla profesional ──────────────────────────────────────────────── */
.tabla-pro thead th {
    background: #f8fafc;
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #64748b;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
    padding: .65rem .85rem;
}
.tabla-pro tbody td { padding: .7rem .85rem; vertical-align: middle; border-color: #f1f5f9; }
.tabla-pro tbody tr:hover { background: #f8fafc; }

/* ── Avatar inicial ─────────────────────────────────────────────────── */
.av {
    width: 34px; height: 34px;
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700;
    flex-shrink: 0;
}
.av-blue   { background: #dbeafe; color: #1d4ed8; }
.av-green  { background: #dcfce7; color: #15803d; }
.av-yellow { background: #fef9c3; color: #92400e; }
.av-red    { background: #fee2e2; color: #b91c1c; }

/* ── Estado pill ────────────────────────────────────────────────────── */
.estado-pill {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .72rem; font-weight: 600;
    border-radius: 2rem;
    padding: .2rem .65rem;
}
.ep-pendiente  { background: #fef9c3; color: #92400e; }
.ep-aprobado   { background: #dcfce7; color: #15803d; }
.ep-rechazado  { background: #fee2e2; color: #b91c1c; }
.ep-finalizado { background: #f1f5f9; color: #475569; }
.ep-critico    { background: #fee2e2; color: #b91c1c; }
.ep-normal     { background: #dbeafe; color: #1d4ed8; }

/* ── Timeline mejorado ──────────────────────────────────────────────── */
.tl-pro { padding-left: 1.5rem; position: relative; }
.tl-pro::before {
    content: '';
    position: absolute;
    left: .35rem; top: 0; bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #dbeafe, #f1f5f9);
}
.tl-item { position: relative; padding-bottom: 1rem; }
.tl-dot {
    position: absolute;
    left: -1.22rem; top: .25rem;
    width: .7rem; height: .7rem;
    border-radius: 50%;
    border: 2px solid #fff;
}
.tl-item .accion { font-size: .82rem; font-weight: 600; color: #1a1a2e; }
.tl-item .detalle { font-size: .78rem; color: #64748b; }
.tl-item .meta    { font-size: .7rem; color: #94a3b8; }

/* ── Alerta vencidos ────────────────────────────────────────────────── */
.alerta-vencidos {
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    border: 1px solid #fed7aa;
    border-radius: .65rem;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
</style>
@endpush

@section('contenido')

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- ALERTA MOVIMIENTOS VENCIDOS                                        --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
@if ($movVencidos->isNotEmpty() && auth()->user()->tienePermiso('MOVIMIENTOS', 'EDITAR'))
    <div class="alerta-vencidos">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
            <div>
                <div class="fw-semibold" style="font-size:.9rem">
                    {{ $movVencidos->count() }} movimiento(s) aprobado(s) con periodo vencido
                </div>
                <div class="text-muted" style="font-size:.78rem">Corresponde cerrarlos como FINALIZADO.</div>
            </div>
        </div>
        <form method="POST" action="{{ route('movimiento.finalizar-vencidos') }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-warning fw-semibold" type="submit">
                <i class="bi bi-flag me-1"></i> Finalizar vencidos
            </button>
        </form>
    </div>
@endif

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- HERO BANNER                                                        --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="db-hero">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

        {{-- Info usuario --}}
        <div>
            <div class="db-hero-title">
                Bienvenido, {{ $usuario->nombre_mostrado }} 👋
            </div>
            <div class="db-hero-sub mb-2">
                Sistema de Gestión del Personal · Red de Salud Tayacaja
            </div>
            <span class="db-hero-badge me-2">
                <i class="bi bi-calendar3 me-1"></i>
                {{ \Illuminate\Support\Carbon::parse($hoy)->isoFormat('dddd D [de] MMMM [de] YYYY') }}
            </span>
            @foreach ($usuario->roles as $rol)
                <span class="db-hero-badge">{{ $rol->nombre }}</span>
            @endforeach
        </div>

        {{-- Quick stats --}}
        <div class="d-flex gap-4 flex-wrap">
            <div class="db-hero-stat">
                <div class="num">{{ $totalPersonal }}</div>
                <div class="lbl">Personal</div>
            </div>
            <div style="width:1px; background:rgba(255,255,255,.2)"></div>
            <div class="db-hero-stat">
                <div class="num">{{ $puntualesHoy + $tardanzasHoy }}</div>
                <div class="lbl">Asistieron hoy</div>
            </div>
            <div style="width:1px; background:rgba(255,255,255,.2)"></div>
            <div class="db-hero-stat">
                <div class="num">{{ $porEstado['PENDIENTE'] ?? 0 }}</div>
                <div class="lbl">Pendientes</div>
            </div>
            <div style="width:1px; background:rgba(255,255,255,.2)"></div>
            <div class="db-hero-stat">
                <div class="num">{{ $cuentasActivas }}</div>
                <div class="lbl">Cuentas activas</div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- SPRINT 1 — PADRÓN DE PERSONAL                                     --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="db-section">
    <div class="db-section-icon icon-blue"><i class="bi bi-people-fill"></i></div>
    <h2>Padrón de Personal</h2>
    <hr>
    
</div>

<div class="row g-3 mb-2">
    <div class="col-sm-6 col-lg-4">
        <div class="card kpi-pro h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="kpi-icon icon-blue"><i class="bi bi-people"></i></div>
                <div>
                    <div class="kpi-num">{{ $totalPersonal }}</div>
                    <div class="kpi-lbl">Total personal</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card kpi-pro h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="kpi-icon icon-green"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="kpi-num text-success">{{ $activos }}</div>
                    <div class="kpi-lbl">Activos</div>
                </div>
                @if ($totalPersonal > 0)
                    <span class="kpi-pill bg-success bg-opacity-10 text-success ms-auto">
                        {{ round($activos / $totalPersonal * 100) }}%
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card kpi-pro h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div class="kpi-icon icon-red"><i class="bi bi-person-x-fill"></i></div>
                <div>
                    <div class="kpi-num text-danger">{{ $inactivos }}</div>
                    <div class="kpi-lbl">Inactivos</div>
                </div>
                @if ($totalPersonal > 0)
                    <span class="kpi-pill bg-danger bg-opacity-10 text-danger ms-auto">
                        {{ round($inactivos / $totalPersonal * 100) }}%
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- SPRINT 2 + 3 — ASISTENCIA HOY  &  MOVIMIENTOS                    --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mt-1">

    {{-- Asistencia hoy --}}
    <div class="col-lg-5">
        <div class="db-section">
            <div class="db-section-icon icon-green"><i class="bi bi-calendar-check"></i></div>
            <h2>Asistencia hoy</h2>
            <hr>
            
        </div>

        @php
            $totalAsistio = $puntualesHoy + $tardanzasHoy + $faltasHoy + $justificados;
        @endphp
        <div class="card asis-bar">
            <div class="card-body px-4 py-3">

                <div class="stat-row">
                    <div class="stat-dot bg-success"></div>
                    <div class="stat-label">Puntuales</div>
                    <div class="stat-count text-success">{{ $puntualesHoy }}</div>
                </div>
                @if ($totalAsistio > 0)
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width:{{ round($puntualesHoy/$totalAsistio*100) }}%"></div>
                    </div>
                @endif

                <div class="stat-row">
                    <div class="stat-dot bg-warning"></div>
                    <div class="stat-label">Tardanzas</div>
                    <div class="stat-count text-warning">{{ $tardanzasHoy }}</div>
                </div>
                @if ($totalAsistio > 0)
                    <div class="progress mb-2">
                        <div class="progress-bar bg-warning" style="width:{{ round($tardanzasHoy/$totalAsistio*100) }}%"></div>
                    </div>
                @endif

                <div class="stat-row">
                    <div class="stat-dot bg-danger"></div>
                    <div class="stat-label">Faltas</div>
                    <div class="stat-count text-danger">{{ $faltasHoy }}</div>
                </div>
                @if ($totalAsistio > 0)
                    <div class="progress mb-2">
                        <div class="progress-bar bg-danger" style="width:{{ round($faltasHoy/$totalAsistio*100) }}%"></div>
                    </div>
                @endif

                <div class="stat-row">
                    <div class="stat-dot bg-info"></div>
                    <div class="stat-label">Justificados</div>
                    <div class="stat-count text-info">{{ $justificados }}</div>
                </div>
                @if ($totalAsistio > 0)
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width:{{ round($justificados/$totalAsistio*100) }}%"></div>
                    </div>
                @endif

                @if ($sinHorario > 0)
                    <div class="stat-row mt-1" style="border-top:1px dashed #f1f5f9; padding-top:.55rem">
                        <div class="stat-dot" style="background:#94a3b8"></div>
                        <div class="stat-label text-muted">Sin horario asignado</div>
                        <div class="stat-count text-muted">{{ $sinHorario }}</div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Movimientos por estado --}}
    <div class="col-lg-7">
        <div class="db-section">
            <div class="db-section-icon icon-purple"><i class="bi bi-arrow-left-right"></i></div>
            <h2>Movimientos Institucionales</h2>
            <hr>
            
        </div>

        <div class="row g-2">
            @foreach ([
                ['Pendientes',  'PENDIENTE',  'icon-yellow', 'bi-hourglass-split',    'ep-pendiente',  'text-warning'],
                ['Aprobados',   'APROBADO',   'icon-green',  'bi-check-circle-fill',  'ep-aprobado',   'text-success'],
                ['Rechazados',  'RECHAZADO',  'icon-red',    'bi-x-circle-fill',      'ep-rechazado',  'text-danger'],
                ['Finalizados', 'FINALIZADO', 'icon-cyan',   'bi-flag-fill',          'ep-finalizado', 'text-secondary'],
            ] as [$lbl, $clave, $iconCls, $icon, $pillCls, $numCls])
                <div class="col-6">
                    <div class="card kpi-pro h-100">
                        <div class="card-body d-flex align-items-center gap-3 py-3">
                            <div class="kpi-icon {{ $iconCls }}"><i class="bi {{ $icon }}"></i></div>
                            <div>
                                <div class="kpi-num {{ $numCls }}">{{ $porEstado[$clave] ?? 0 }}</div>
                                <div class="kpi-lbl">{{ $lbl }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Movimientos en curso hoy --}}
        @if ($enCurso->isNotEmpty())
            <div class="card mt-2" style="border-radius:.65rem; border:0; box-shadow:0 1px 4px rgba(0,0,0,.07)">
                <div class="card-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-radius:.65rem .65rem 0 0; font-size:.82rem; font-weight:600">
                    <i class="bi bi-arrow-left-right text-success me-1"></i> En curso hoy
                    <span class="badge text-bg-success ms-1">{{ $enCurso->count() }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table tabla-pro mb-0">
                        <thead><tr><th>Trabajador</th><th>Tipo</th><th>Hasta</th></tr></thead>
                        <tbody>
                        @foreach ($enCurso->take(4) as $m)
                            <tr>
                                <td>
                                    <a href="{{ route('movimiento.show', $m) }}" class="text-decoration-none fw-semibold" style="font-size:.85rem">
                                        {{ $m->personal?->nombre_completo }}
                                    </a>
                                    <div class="text-muted" style="font-size:.72rem">{{ $m->personal?->area?->nombre }}</div>
                                </td>
                                <td style="font-size:.82rem">{{ str_replace('_', ' ', $m->tipo?->nombre ?? '') }}</td>
                                <td style="font-size:.82rem">{{ $m->fecha_fin?->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- TABLAS: INCORPORACIONES + PENDIENTES                              --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mt-1">

    {{-- Últimas incorporaciones --}}
    <div class="col-lg-6">
        <div class="db-section">
            <div class="db-section-icon icon-blue"><i class="bi bi-person-plus-fill"></i></div>
            <h2>Últimas incorporaciones</h2>
            <hr>
            
        </div>
        <div class="card" style="border-radius:.65rem; border:0; box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="table-responsive">
                <table class="table tabla-pro mb-0">
                    <thead>
                        <tr><th>Trabajador</th><th>Área</th><th>Ingreso</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($ultimas as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="av av-blue">
                                        {{ strtoupper(substr($p->nombres, 0, 1)) }}{{ strtoupper(substr($p->apellidos, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('personal.show', $p) }}" class="text-decoration-none fw-semibold" style="font-size:.85rem">
                                            {{ $p->nombre_completo }}
                                        </a>
                                        <div class="text-muted font-monospace" style="font-size:.7rem">{{ $p->dni }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.82rem">{{ $p->area?->nombre ?? '—' }}</td>
                            <td style="font-size:.82rem">{{ $p->fecha_ingreso?->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:.85rem">Sin registros recientes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Movimientos pendientes de decisión --}}
    <div class="col-lg-6">
        <div class="db-section">
            <div class="db-section-icon icon-yellow"><i class="bi bi-hourglass-split"></i></div>
            <h2>Pendientes de decisión</h2>
            <hr>
            
        </div>
        <div class="card" style="border-radius:.65rem; border:0; box-shadow:0 1px 4px rgba(0,0,0,.07)">
            <div class="table-responsive">
                <table class="table tabla-pro mb-0">
                    <thead>
                        <tr><th>Trabajador</th><th>Tipo</th><th>Días</th></tr>
                    </thead>
                    <tbody>
                    @forelse ($movPendientes as $m)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="av av-yellow">
                                        {{ strtoupper(substr($m->personal?->nombres ?? '?', 0, 1)) }}{{ strtoupper(substr($m->personal?->apellidos ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('movimiento.show', $m) }}" class="text-decoration-none fw-semibold" style="font-size:.85rem">
                                            {{ $m->personal?->nombre_completo }}
                                        </a>
                                        <div class="text-muted" style="font-size:.72rem">{{ $m->personal?->area?->nombre }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.82rem">{{ str_replace('_', ' ', $m->tipo?->nombre ?? '') }}</td>
                            <td>
                                <span class="kpi-pill ep-pendiente">{{ $m->dias }}d</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:.85rem">Sin movimientos pendientes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- SPRINT 4 — USUARIOS Y SEGURIDAD                                   --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="db-section mt-2">
    <div class="db-section-icon icon-purple"><i class="bi bi-shield-lock-fill"></i></div>
    <h2>Gestión de Usuarios</h2>
    <hr>
    
</div>

<div class="row g-3">
    @foreach ([
        ['Cuentas activas',   $cuentasActivas,         'icon-green',  'bi-person-check-fill',          'text-success'],
        ['Inactivas',         $cuentasInactivas,        'icon-cyan',   'bi-person-dash',                'text-info'],
        ['Bloqueadas',        $cuentasBloqueadasCount,  'icon-red',    'bi-lock-fill',                  'text-danger'],
        ['Sin rol asignado',  $sinRol,                  'icon-yellow', 'bi-exclamation-triangle-fill',  'text-warning'],
    ] as [$lbl, $val, $iconCls, $icon, $numCls])
        <div class="col-6 col-lg-3">
            <div class="card kpi-pro h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="kpi-icon {{ $iconCls }}"><i class="bi {{ $icon }}"></i></div>
                    <div>
                        <div class="kpi-num {{ $numCls }}">{{ $val }}</div>
                        <div class="kpi-lbl">{{ $lbl }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if ($sinCuenta > 0 && auth()->user()->tienePermiso('USUARIOS', 'LEER'))
    <div class="d-flex align-items-center gap-2 mt-3 px-3 py-2 rounded" style="background:#f0f9ff; border:1px solid #bae6fd; font-size:.84rem">
        <i class="bi bi-info-circle text-info"></i>
        <span>Hay <strong>{{ $sinCuenta }}</strong> trabajador(es) activo(s) sin cuenta de acceso.</span>
    </div>
@endif

{{-- Cuentas bloqueadas + eventos de seguridad --}}
@if (auth()->user()->tienePermiso('USUARIOS', 'LEER'))
    <div class="row g-3 mt-1">

        @if ($cuentasBloqueadasLista->isNotEmpty())
            <div class="col-lg-5">
                <div class="db-section">
                    <div class="db-section-icon icon-red"><i class="bi bi-lock-fill"></i></div>
                    <h2>Cuentas bloqueadas</h2>
                    <hr>
                    <span class="badge text-bg-danger">{{ $cuentasBloqueadasLista->count() }}</span>
                </div>
                <div class="card" style="border-radius:.65rem; border:0; box-shadow:0 1px 4px rgba(0,0,0,.07)">
                    <div class="table-responsive">
                        <table class="table tabla-pro mb-0">
                            <thead><tr><th>Usuario</th><th>Intentos</th><th class="no-imprimir">Acción</th></tr></thead>
                            <tbody>
                            @foreach ($cuentasBloqueadasLista as $u)
                                <tr>
                                    <td>
                                        <a href="{{ route('usuario.show', $u) }}" class="text-decoration-none fw-semibold font-monospace" style="font-size:.83rem">
                                            {{ $u->username }}
                                        </a>
                                        <div class="text-muted" style="font-size:.72rem">{{ $u->personal?->nombre_completo }}</div>
                                    </td>
                                    <td>
                                        <span class="estado-pill ep-rechazado">
                                            <i class="bi bi-x-circle-fill"></i> {{ $u->intentos_fallidos }}
                                        </span>
                                    </td>
                                    <td class="no-imprimir">
                                        <form method="POST" action="{{ route('usuario.desbloquear', $u) }}" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-warning" type="submit" title="Desbloquear">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Eventos de seguridad --}}
        <div class="{{ $cuentasBloqueadasLista->isNotEmpty() ? 'col-lg-7' : 'col-12' }}">
            <div class="db-section">
                <div class="db-section-icon icon-blue"><i class="bi bi-shield-exclamation"></i></div>
                <h2>Eventos de seguridad</h2>
                <hr>
                @if ($accesosFallidos24h > 0)
                    <span class="estado-pill ep-rechazado">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $accesosFallidos24h }} fallo(s) en 24h
                    </span>
                @endif
            </div>
            <div class="card" style="border-radius:.65rem; border:0; box-shadow:0 1px 4px rgba(0,0,0,.07)">
                <div class="card-body py-3" style="max-height:320px; overflow-y:auto">
                    <div class="tl-pro">
                    @forelse ($eventosSeg as $log)
                        @php($critico = in_array($log->accion, ['ACCESO_FALLIDO','BLOQUEAR_CUENTA','ACCESO_BLOQUEADO'], true))
                        <div class="tl-item">
                            <div class="tl-dot {{ $critico ? 'bg-danger' : 'bg-primary' }}"></div>
                            <div class="d-flex align-items-start gap-2">
                                <div class="flex-grow-1">
                                    <span class="accion">
                                        <span class="estado-pill {{ $critico ? 'ep-critico' : 'ep-normal' }} me-1" style="font-size:.65rem">
                                            {{ str_replace('_', ' ', $log->accion) }}
                                        </span>
                                    </span>
                                    @if ($log->detalle)
                                        <div class="detalle">{{ $log->detalle }}</div>
                                    @endif
                                    <div class="meta">
                                        <i class="bi bi-clock me-1"></i>{{ $log->fecha?->format('d/m/Y H:i') }}
                                        · <i class="bi bi-person me-1"></i>{{ $log->usuario?->username ?? 'sistema' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted" style="font-size:.85rem">Sin eventos registrados.</p>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
@endif

@endsection
