<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Límite general por IP (además del bloqueo por cuenta de RF001), uno por formulario
        $demasiados = fn () => back()->withInput()
            ->withErrors(['telefono' => 'Demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.'])
            ->with('err', 'Demasiadas solicitudes. Espera un minuto e inténtalo de nuevo.');

        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(20)->by('login|' . $r->ip())->response($demasiados));
        RateLimiter::for('recuperar', fn (Request $r) => Limit::perMinute(5)->by('recuperar|' . $r->ip())->response($demasiados));

        // Menú de la tienda: categorías que tienen algo a la venta
        View::composer('htmlv.layout', function ($view) {
            $view->with('navCategorias', Categoria::whereHas('productos', fn ($q) => $q->where('Activo', true)->where('Stock', '>', 0))
                ->orderBy('Nombre')->get(['Id_categoria', 'Nombre']));
        });
    }
}
