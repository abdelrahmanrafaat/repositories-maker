<?php

namespace Abdelrahmanrafaat\RepositoriesMaker\Provider;

use Illuminate\Support\ServiceProvider;

class RepositoriesMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \Abdelrahmanrafaat\RepositoriesMaker\Command\MakeRepositories::class,
        ]);
    }
}
