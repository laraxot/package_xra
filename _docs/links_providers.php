http://laravel-recipes.com/recipes/199/registering-a-service-provider-with-the-application
<?php
App::register('MyApp\Providers\MyServiceProvider');

// or
$provider = new MyApp\Providers\MyServiceProvider();
App::register($provider);

?>

https://mattstauffer.co/blog/conditionally-loading-service-providers-in-laravel-5

<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;

class links_providers extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
    }

    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        if ($this->app->environment('production')) {
            $this->app->register('App\Providers\ProductionErrorHandlerServiceProvider');
        } else {
            $this->app->register('App\Providers\VerboseErrorHandlerServiceProvider');
        }
    }
}
