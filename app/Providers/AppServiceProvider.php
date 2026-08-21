<?php

declare(strict_types=1);

namespace App\Providers;

use App\Modelo\CatalogoCasosUso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Configuracion global de la aplicacion SISARST.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // La paginacion se dibuja con Bootstrap 5.3, no con Tailwind.
        Paginator::useBootstrapFive();

        // Fechas en espanol en toda la interfaz (RNF-05).
        Date::setLocale(config('app.locale', 'es'));
        setlocale(LC_TIME, 'es_PE.UTF-8', 'es_ES.UTF-8', 'Spanish');

        // Evita altas o ediciones silenciosas por atributos no declarados
        // en $fillable, lo que refuerza la integridad de datos (RNF-08).
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        // Cada vista recibe el caso de uso que implementa la pantalla y el
        // sprint al que pertenece, para poder rotularlos en la interfaz
        // segun el documento de Diseno (Diagramas de Casos de Uso).
        View::composer('*', function ($vista): void {
            $caso = CatalogoCasosUso::actual();

            $vista->with([
                'casoUso' => $caso,
                'sprintUso' => $caso !== null
                    ? CatalogoCasosUso::sprint((int) $caso['sprint'])
                    : null,
            ]);
        });
    }
}
