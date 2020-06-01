<?php

namespace MemoChou1993\Localize;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MemoChou1993\Localize\Console\ClearCommand;
use MemoChou1993\Localize\Console\ExportCommand;

class LocalizeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/localize.php', 'localize'
        );

        $this->app->singleton('localize', function() {
            return new Localize();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/localize.php' => config_path('localize.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportCommand::class,
                ClearCommand::class,
            ]);
        }

        Route::group([
            'namespace' => 'MemoChou1993\Localize\Http\Controllers',
            'prefix' => config('localize.path'),
            // 'middleware' => config('telescope.middleware', []), // TODO
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }
}
