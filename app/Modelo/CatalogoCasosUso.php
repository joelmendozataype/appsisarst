<?php

declare(strict_types=1);

namespace App\Modelo;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Capa MODELO - Catalogo de casos de uso del sistema.
 *
 * Resuelve, a partir del nombre de la ruta activa, el caso de uso que la
 * pantalla implementa segun el documento de Diseno (Diagramas de Casos de
 * Uso, secciones 1.1 a 5.1). Su proposito es que cada ventana y cada opcion
 * del menu muestre el nombre del caso de uso al que pertenece, con la
 * trazabilidad CU <-> HU <-> RF visible en la interfaz.
 *
 * La fuente de datos es config/casos_uso.php.
 */
class CatalogoCasosUso
{
    /** Cache en memoria del catalogo normalizado. */
    private static ?Collection $casos = null;

    /**
     * Todos los casos de uso del sistema, en el orden del documento.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function todos(): Collection
    {
        if (self::$casos === null) {
            self::$casos = collect(config('casos_uso.casos', []))
                ->map(fn (array $caso): array => $caso + [
                    'menu' => false,
                    'ruta_menu' => null,
                    'rutas' => [],
                    'icono' => 'bi-record-circle',
                    'actor' => '',
                    'descripcion' => '',
                ])
                ->values();
        }

        return self::$casos;
    }

    /**
     * Datos del sprint indicado.
     *
     * @return array<string, mixed>|null
     */
    public static function sprint(int $numero): ?array
    {
        return config("casos_uso.sprints.$numero");
    }

    /**
     * Todos los sprints del sistema, ordenados.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function sprints(): Collection
    {
        return collect(config('casos_uso.sprints', []))->sortBy('numero');
    }

    /**
     * Casos de uso pertenecientes a un sprint.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function delSprint(int $numero): Collection
    {
        return self::todos()->where('sprint', $numero)->values();
    }

    /**
     * Casos de uso de un sprint que tienen entrada propia en el menu.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function menuDelSprint(int $numero): Collection
    {
        return self::delSprint($numero)
            ->filter(fn (array $c): bool => $c['menu'] === true && $c['ruta_menu'] !== null)
            // Las pantallas de consulta encabezan el submenu; las de registro
            // van despues, siguiendo el flujo habitual de trabajo.
            ->sortBy(fn (array $c): int => str_ends_with((string) $c['ruta_menu'], '.index') ? 0 : 1)
            ->values();
    }

    /**
     * Caso de uso identificado por su codigo (por ejemplo "CU-03").
     *
     * @return array<string, mixed>|null
     */
    public static function porCodigo(string $codigo): ?array
    {
        return self::todos()->firstWhere('codigo', $codigo);
    }

    /**
     * Caso de uso que corresponde a un nombre de ruta.
     *
     * @return array<string, mixed>|null
     */
    public static function porRuta(?string $ruta): ?array
    {
        if ($ruta === null || $ruta === '') {
            return null;
        }

        // 1) Coincidencia exacta con alguna de las rutas declaradas.
        $exacto = self::todos()->first(
            fn (array $c): bool => in_array($ruta, $c['rutas'], true)
        );

        if ($exacto !== null) {
            return $exacto;
        }

        // 2) Respaldo por prefijo del modulo ("personal.", "reporte.", ...),
        //    para que una ruta nueva no deje la pantalla sin rotular.
        $prefijo = str_contains($ruta, '.') ? explode('.', $ruta)[0].'.' : $ruta;

        return self::todos()->first(function (array $c) use ($prefijo): bool {
            foreach ($c['rutas'] as $declarada) {
                if (str_starts_with($declarada, $prefijo)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Caso de uso de la pantalla que se esta mostrando.
     *
     * @return array<string, mixed>|null
     */
    public static function actual(): ?array
    {
        return self::porRuta(Route::currentRouteName());
    }

    /**
     * Rotulo que se muestra en pantalla: solo el nombre del caso de uso.
     */
    public static function rotulo(?array $caso): string
    {
        return $caso['nombre'] ?? '';
    }

    /**
     * Trazabilidad completa del caso de uso: "Gestion del Padron de
     * Personal · HU-03 · RF-03".
     *
     * NO se muestra en la interfaz: la pantalla solo exhibe el NOMBRE del
     * caso de uso. Este metodo queda disponible para la documentacion y
     * para las pruebas que verifican la correspondencia con el diseno.
     */
    public static function trazabilidad(?array $caso): string
    {
        if ($caso === null) {
            return '';
        }

        $sprint = self::sprint((int) $caso['sprint']);

        return trim(sprintf(
            '%s · %s · %s · %s',
            $sprint['nombre'] ?? '',
            $caso['codigo'],
            $caso['hu'],
            $caso['rf']
        ), ' ·');
    }
}
