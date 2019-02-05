<?php
namespace XRA\XRA;

//https://medium.com/@NahidulHasan/how-to-use-macros-in-laravel-a9078a0610f9

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

use Laravel\Scout\EngineManager; // per slegarmi da tntsearch

use XRA\Extend\Traits\ServiceProviderTrait;
//use XRA\XRA\Services\CustomInputService;
use XRA\XRA\Services\FullTextSearchEngine;



class XRAServiceProvider extends ServiceProvider
{
    use ServiceProviderTrait{
        boot as protected bootTrait;
        register as protected registerTrait;
    }

    /**
     * Bootstrap the application services.
     * https://github.com/appstract/laravel-blade-directives/blob/master/src/BladeDirectivesServiceProvider.php
     * https://meritocracy.is/blog/2017/09/11/3-laravel-blade-directives-will-save-time/
     * https://laracasts.com/discuss/channels/laravel/useful-blade-directives.
     */
    public function boot(\Illuminate\Routing\Router $router)
    {
        // Load middlewares
        $router->aliasMiddleware('checkarea', Middleware\CheckArea::class);
        //dd($_SERVER);
        if (isset($_SERVER['SERVER_NAME']) && 'localhost' != $_SERVER['SERVER_NAME']
            && isset($_SERVER['REQUEST_SCHEME']) && 'https' == $_SERVER['REQUEST_SCHEME']
            //&& substr($_SERVER['SERVER_NAME'],0,strlen('www.'))=='www.'
        ) {
            URL::forceScheme('https');
        }

        resolve(EngineManager::class)->extend('fulltext', function () {
            return new FullTextSearchEngine;
        });

        Blade::if('prod', function () {
            return app()->environment('production');
        });
        Blade::if('env', function ($env) {
            return app()->environment($env);
        });

        Blade::directive('rn', function ($name) {
            return '<?php if(Route::currentRouteName() == $name) echo "active"; ?>';
        });

        $this->bootTrait($router);
        $enable_packs = config('xra.enable_packs');
        //echo '<pre>'.print_r($enable_packs).'</pre>';
        //die('['.__LINE__.']['.__FILE__.']');
        $namespaces = [];
        foreach (Packages::allVendors() as $vendor) {
            foreach (Packages::all($vendor) as $package) {
                $provider = Packages::provider($package, $vendor);
                //echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug

                //dd($migrate_packs);
                // echo '<pre>'.strtolower($package).'</pre>';

                if (!\is_array($enable_packs) || (\is_array($enable_packs) && \in_array(\mb_strtolower($package), $enable_packs, true))) {
                    //echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
                    if ($provider) {
                        //echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
                        $tmp = $vendor.'\\'.$package.'\\'.$provider;
                        $namespaces[$package] = $vendor.'\\'.$package;
                        $this->app->register($tmp);
                    }//endif
                }
            }//endforeach
        }//endforeach
        \Config::set('xra.namespaces', $namespaces);
        $this->bootTrait($router);
    }

    //end function
    /* //--- non funziona.. fare test per farlo funzionare o si cancella
    public function register(){
        $this->registerTrait();

        $this->app->singleton('form', function ($app) {
            $form = new CustomInputService($app['html'], $app['url'], $app['view'], $app['session.store']->token());
            return $form->setSessionStore($app['session.store']);
        });

    }
    */

//--------------------------
}//end class
