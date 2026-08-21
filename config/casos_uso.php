<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Catalogo de Casos de Uso del sistema SISARST
|--------------------------------------------------------------------------
|
| Fuente: documento "DISENO DE LA ETAPA DEL CICLO DE DESARROLLO DEL SISTEMA
| WEB INTEGRADO PARA LA GESTION DEL PERSONAL DE LA RED DE SALUD TAYACAJA"
| (seccion 1.1 a 5.1, Diagramas de Casos de Uso por Sprint) y documento de
| ANALISIS (Tabla 4 - Requerimientos Funcionales, Tabla 7 - Product Backlog).
|
| Este archivo es la UNICA fuente de verdad para los nombres de los casos de
| uso que se muestran en la interfaz. Cada pantalla del sistema queda
| rotulada con el caso de uso que implementa, de modo que exista trazabilidad
| visible entre el codigo y el diseno:
|
|      CU  <->  HU (historia de usuario)  <->  RF (requerimiento funcional)
|
*/

return [

    /*
    |----------------------------------------------------------------------
    | Sprints (modulos funcionales del sistema)
    |----------------------------------------------------------------------
    */
    'sprints' => [

        1 => [
            'numero' => 1,
            'nombre' => 'Gestion del Padron de Personal',
            'periodo' => '25/05/2026 - 13/06/2026',
            'rf' => 'RF-01 a RF-04, RF-20',
            'icono' => 'bi-people-fill',
        ],

        2 => [
            'numero' => 2,
            'nombre' => 'Control de Asistencia',
            'periodo' => '16/06/2026 - 28/06/2026',
            'rf' => 'RF-05, RF-06, RF-07, RF-19',
            'icono' => 'bi-calendar-check',
        ],

        3 => [
            'numero' => 3,
            'nombre' => 'Gestion de Movimientos Institucionales',
            'periodo' => '30/06/2026 - 12/07/2026',
            'rf' => 'RF-08, RF-09, RF-10',
            'icono' => 'bi-arrow-left-right',
        ],

        4 => [
            'numero' => 4,
            'nombre' => 'Gestion de Usuarios y Seguridad',
            'periodo' => '14/07/2026 - 25/07/2026',
            'rf' => 'RF-11, RF-12, RF-13, RF-14',
            'icono' => 'bi-person-badge',
        ],

        5 => [
            'numero' => 5,
            'nombre' => 'Reportes Administrativos',
            'periodo' => '27/07/2026 - 08/08/2026',
            'rf' => 'RF-15, RF-16, RF-17, RF-18',
            'icono' => 'bi-file-earmark-bar-graph',
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Casos de uso
    |----------------------------------------------------------------------
    |
    | codigo   : identificador del caso de uso en el diagrama de casos de uso
    | nombre   : nombre EXACTO del caso de uso segun el documento de diseno
    | sprint   : sprint al que pertenece
    | hu / rf  : trazabilidad con el documento de analisis
    | rutas    : nombres de ruta (routes/web.php) que ejecutan este caso de uso
    | menu     : true si el caso de uso tiene una entrada propia en el menu
    | ruta_menu: ruta a la que apunta la entrada del menu
    | icono    : icono Bootstrap Icons
    | actor    : actores que participan (diagrama de casos de uso)
    |
    */
    'casos' => [

        // ── SPRINT 1: Gestion del Padron de Personal ───────────────────
        [
            'codigo' => 'CU-01',
            'nombre' => 'Registrar Personal',
            'sprint' => 1,
            'hu' => 'HU-01',
            'rf' => 'RF-01',
            'actor' => 'Administrador de RRHH',
            'rutas' => ['personal.create', 'personal.store'],
            'menu' => true,
            'ruta_menu' => 'personal.create',
            'icono' => 'bi-person-plus-fill',
            'descripcion' => 'Registrar datos personales, laborales y de contacto del personal.',
        ],
        [
            'codigo' => 'CU-02',
            'nombre' => 'Editar Datos del Personal',
            'sprint' => 1,
            'hu' => 'HU-02',
            'rf' => 'RF-02',
            'actor' => 'Administrador de RRHH',
            'rutas' => ['personal.edit', 'personal.update'],
            'menu' => false,
            'icono' => 'bi-pencil-square',
            'descripcion' => 'Modificar la informacion del personal previamente registrado.',
        ],
        [
            'codigo' => 'CU-03',
            'nombre' => 'Consultar Padron de Personal',
            'sprint' => 1,
            'hu' => 'HU-03',
            'rf' => 'RF-03',
            'actor' => 'Administrador de RRHH / Jefe de Area',
            'rutas' => ['personal.index'],
            'menu' => true,
            'ruta_menu' => 'personal.index',
            'icono' => 'bi-person-lines-fill',
            'descripcion' => 'Consultar el listado completo con filtros por area, cargo y condicion.',
        ],
        [
            'codigo' => 'CU-04',
            'nombre' => 'Desactivar Personal',
            'sprint' => 1,
            'hu' => 'HU-04',
            'rf' => 'RF-04',
            'actor' => 'Administrador de RRHH',
            'rutas' => ['personal.desactivar', 'personal.reactivar'],
            'menu' => false,
            'icono' => 'bi-person-dash-fill',
            'descripcion' => 'Baja logica del personal que ya no pertenece a la institucion.',
        ],
        [
            'codigo' => 'CU-05',
            'nombre' => 'Consultar Historial de Personal',
            'sprint' => 1,
            'hu' => 'HU-18',
            'rf' => 'RF-20',
            'actor' => 'Administrador de RRHH / Jefe de Area',
            'rutas' => ['personal.show'],
            'menu' => false,
            'icono' => 'bi-clock-history',
            'descripcion' => 'Historial laboral completo: datos, asistencias y movimientos.',
        ],

        // ── SPRINT 2: Control de Asistencia ────────────────────────────
        [
            'codigo' => 'CU-06',
            'nombre' => 'Registrar Asistencia',
            'sprint' => 2,
            'hu' => 'HU-05',
            'rf' => 'RF-05',
            'actor' => 'Personal de RRHH',
            'rutas' => ['asistencia.create', 'asistencia.store', 'asistencia.edit', 'asistencia.update'],
            'menu' => true,
            'ruta_menu' => 'asistencia.create',
            'icono' => 'bi-clipboard-check',
            'descripcion' => 'Registrar la asistencia diaria del personal (entrada y salida).',
        ],
        [
            'codigo' => 'CU-07',
            'nombre' => 'Consultar Asistencia por Periodo',
            'sprint' => 2,
            'hu' => 'HU-06',
            'rf' => 'RF-06',
            'actor' => 'Jefe de Area / Personal de RRHH',
            'rutas' => ['asistencia.index'],
            'menu' => true,
            'ruta_menu' => 'asistencia.index',
            'icono' => 'bi-calendar-check',
            'descripcion' => 'Consultar registros de asistencia por personal, area y periodo.',
        ],
        [
            'codigo' => 'CU-08',
            'nombre' => 'Registro Automatico de Tardanzas y Faltas',
            'sprint' => 2,
            'hu' => 'HU-07',
            'rf' => 'RF-07',
            'actor' => 'Sistema (tarea programada)',
            'rutas' => [],
            'menu' => false,
            'icono' => 'bi-robot',
            'descripcion' => 'La tardanza se evalua en la marcacion; la falta la genera la tarea programada al cierre de la jornada.',
        ],
        [
            'codigo' => 'CU-09',
            'nombre' => 'Registrar y Asignar Horarios',
            'sprint' => 2,
            'hu' => 'HU-16',
            'rf' => 'RF-19',
            'actor' => 'Administrador de RRHH',
            'rutas' => [
                'horario.index', 'horario.create', 'horario.store', 'horario.edit',
                'horario.update', 'horario.asignar.form', 'horario.asignar',
                'horario.desactivar', 'horario.reactivar', 'horario.quitar',
            ],
            'menu' => true,
            'ruta_menu' => 'horario.index',
            'icono' => 'bi-clock',
            'descripcion' => 'Registrar horarios de trabajo (entrada, salida, tolerancia) y asignarlos al personal.',
        ],

        // ── SPRINT 3: Gestion de Movimientos Institucionales ───────────
        [
            'codigo' => 'CU-10',
            'nombre' => 'Registrar Movimiento Institucional',
            'sprint' => 3,
            'hu' => 'HU-08',
            'rf' => 'RF-08',
            'actor' => 'Administrador de RRHH',
            'rutas' => ['movimiento.create', 'movimiento.store', 'movimiento.edit', 'movimiento.update'],
            'menu' => true,
            'ruta_menu' => 'movimiento.create',
            'icono' => 'bi-file-earmark-plus',
            'descripcion' => 'Registrar comisiones, rotaciones, licencias y permisos del personal.',
        ],
        [
            'codigo' => 'CU-11',
            'nombre' => 'Consultar Movimientos Institucionales',
            'sprint' => 3,
            'hu' => 'HU-09',
            'rf' => 'RF-09',
            'actor' => 'Jefe de Area / Administrador de RRHH',
            'rutas' => ['movimiento.index', 'movimiento.show'],
            'menu' => true,
            'ruta_menu' => 'movimiento.index',
            'icono' => 'bi-arrow-left-right',
            'descripcion' => 'Consultar el historial de movimientos por personal y periodo.',
        ],
        [
            'codigo' => 'CU-12',
            'nombre' => 'Actualizar Estado del Movimiento',
            'sprint' => 3,
            'hu' => 'HU-10',
            'rf' => 'RF-10',
            'actor' => 'Administrador de RRHH',
            'rutas' => ['movimiento.estado', 'movimiento.finalizar-vencidos'],
            'menu' => false,
            'icono' => 'bi-check2-circle',
            'descripcion' => 'Aprobar, rechazar o finalizar movimientos institucionales registrados.',
        ],

        // ── SPRINT 4: Gestion de Usuarios y Seguridad ──────────────────
        [
            'codigo' => 'CU-13',
            'nombre' => 'Gestionar Usuarios del Sistema',
            'sprint' => 4,
            'hu' => 'HU-11',
            'rf' => 'RF-11',
            'actor' => 'Administrador del Sistema',
            'rutas' => [
                'usuario.index', 'usuario.create', 'usuario.store', 'usuario.show',
                'usuario.edit', 'usuario.update', 'usuario.desactivar',
                'usuario.reactivar', 'usuario.desbloquear',
                'usuario.clave.form', 'usuario.clave',
            ],
            'menu' => true,
            'ruta_menu' => 'usuario.index',
            'icono' => 'bi-person-badge',
            'descripcion' => 'Crear, editar y desactivar usuarios con acceso al sistema.',
        ],
        [
            'codigo' => 'CU-14',
            'nombre' => 'Asignar Roles y Permisos',
            'sprint' => 4,
            'hu' => 'HU-12',
            'rf' => 'RF-12',
            'actor' => 'Administrador del Sistema',
            'rutas' => ['rol.index', 'rol.edit', 'rol.update'],
            'menu' => true,
            'ruta_menu' => 'rol.index',
            'icono' => 'bi-shield-lock',
            'descripcion' => 'Asignar roles y permisos diferenciados por modulo y accion.',
        ],
        [
            'codigo' => 'CU-15',
            'nombre' => 'Iniciar Sesion (Autenticacion)',
            'sprint' => 4,
            // Deriva directamente del requerimiento funcional: en el diseno
            // aparece como "DS-RF13", sin historia de usuario propia, porque
            // es la relacion <<include>> de todos los demas casos de uso.
            'hu' => '',
            'rf' => 'RF-13',
            'actor' => 'Usuario del Sistema',
            'rutas' => ['login', 'login.store', 'logout'],
            'menu' => false,
            'icono' => 'bi-box-arrow-in-right',
            'descripcion' => 'Acceso mediante usuario y contrasena con validacion segura y bloqueo por intentos.',
        ],
        [
            'codigo' => 'CU-16',
            'nombre' => 'Recuperar Contrasena',
            'sprint' => 4,
            'hu' => 'HU-17',
            'rf' => 'RF-14',
            'actor' => 'Usuario del Sistema',
            'rutas' => [
                'recuperacion.solicitar', 'recuperacion.enviar',
                'recuperacion.formulario', 'recuperacion.restablecer',
            ],
            'menu' => false,
            'icono' => 'bi-key',
            'descripcion' => 'Recuperacion de contrasena mediante correo electronico institucional.',
        ],

        // ── SPRINT 5: Reportes Administrativos ─────────────────────────
        [
            'codigo' => 'CU-17',
            'nombre' => 'Generar Reporte de Personal',
            'sprint' => 5,
            'hu' => 'HU-13',
            'rf' => 'RF-15',
            'actor' => 'Administrador de RRHH',
            // reporte.index abre justamente la pestana de personal.
            'rutas' => ['reporte.index', 'reporte.personal'],
            'menu' => true,
            'ruta_menu' => 'reporte.index',
            'icono' => 'bi-people',
            'descripcion' => 'Reportes del padron por area, cargo, condicion y establecimiento.',
        ],
        [
            'codigo' => 'CU-18',
            'nombre' => 'Generar Reporte de Asistencia',
            'sprint' => 5,
            'hu' => 'HU-14',
            'rf' => 'RF-16',
            'actor' => 'Jefe de Area',
            'rutas' => ['reporte.asistencia'],
            'menu' => true,
            'ruta_menu' => 'reporte.asistencia',
            'icono' => 'bi-calendar-range',
            'descripcion' => 'Reportes de asistencia por personal, area y periodo.',
        ],
        [
            'codigo' => 'CU-19',
            'nombre' => 'Generar Reporte de Movimientos',
            'sprint' => 5,
            'hu' => 'HU-15',
            'rf' => 'RF-17',
            'actor' => 'Gerente de la Red',
            'rutas' => ['reporte.movimientos'],
            'menu' => true,
            'ruta_menu' => 'reporte.movimientos',
            'icono' => 'bi-diagram-3',
            'descripcion' => 'Reportes consolidados de movimientos por personal y periodo.',
        ],
        [
            'codigo' => 'CU-20',
            'nombre' => 'Exportar Reportes',
            'sprint' => 5,
            'hu' => 'HU-13, HU-14, HU-15',
            'rf' => 'RF-18',
            'actor' => 'Administrador de RRHH / Jefe de Area / Gerente de la Red',
            // No es una pantalla: es la accion PDF/Excel que ofrece cada uno
            // de los tres reportes anteriores.
            'rutas' => [],
            'menu' => false,
            'icono' => 'bi-file-earmark-arrow-down',
            'descripcion' => 'Exportar los reportes generados en formato PDF o Excel.',
        ],
    ],
];
