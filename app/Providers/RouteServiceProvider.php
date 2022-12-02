<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * El path a la ruta "inicio" para el sistema
     *
     * Esto lo utiliza la autenticación de Laravel para redirigir a los usuarios después de iniciar sesión
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * El espacio de nombres del controlador para el sistema
     *
     * Cuando esté presente, las declaraciones de ruta del controlador tendrán automáticamente el prefijo de este espacio de nombres
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define los enlaces del modelo de ruta, los filtros de patrón, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configura los limitadores de velocidad para la aplicación.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
