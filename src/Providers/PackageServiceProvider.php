<?php

namespace Devertix\LaravelApiGenerator\Providers;

use Devertix\LaravelApiGenerator\Console\MakeApiResourceCommand;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('devertix.make.apiresource', function () {
            return new MakeApiResourceCommand();
        });
        $this->commands('devertix.make.apiresource');
    }
}
