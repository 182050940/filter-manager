<?php

namespace Laoliu\FilterManager;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\ServiceProvider;

class FilterManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('FilterManager', function () {
            return new FilterManager(Input::all(), '', ['page']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('FilterManager');
    }
}
