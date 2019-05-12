<?php
namespace XRA\XRA;
use Illuminate\Support\ServiceProvider;

use XRA\Extend\BaseServiceProvider;
//https://medium.com/@NahidulHasan/how-to-use-macros-in-laravel-a9078a0610f9

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

use Laravel\Scout\EngineManager; // per slegarmi da tntsearch

use XRA\Extend\Traits\ServiceProviderTrait;
//use XRA\XRA\Services\CustomInputService;
use XRA\XRA\Services\FullTextSearchEngine;


class XRAServiceProvider 
	//extends BaseServiceProvider
	extends ServiceProvider
	{
	//*
	use ServiceProviderTrait{
		boot as protected bootTrait;
		register as protected registerTrait;
	}
	//*/
	

	/**
	 * Bootstrap the application services.
	 * https://github.com/appstract/laravel-blade-directives/blob/master/src/BladeDirectivesServiceProvider.php
	 * https://meritocracy.is/blog/2017/09/11/3-laravel-blade-directives-will-save-time/
	 * https://laracasts.com/discuss/channels/laravel/useful-blade-directives.
	 */
	/*
	public function provides(){
		dd('['.__LINE__.']['.__FILE__.']provides'); // 1
    }
    //*/
    /*
	public function register(){
	 	dd('['.__LINE__.']['.__FILE__.']register');	// 2
	}
	*/

	public function boot(\Illuminate\Routing\Router $router){
		//dd('['.__LINE__.']['.__FILE__.']boot');
		// Load middlewares
		//$router->aliasMiddleware('checkarea', Middleware\CheckArea::class);
		//ddd($_SERVER);
		if (isset($_SERVER['SERVER_NAME']) && 'localhost' != $_SERVER['SERVER_NAME']
			&& isset($_SERVER['REQUEST_SCHEME']) && 'https' == $_SERVER['REQUEST_SCHEME']
			//&& substr($_SERVER['SERVER_NAME'],0,strlen('www.'))=='www.'
		) {
			URL::forceScheme('https');
		}

		resolve(EngineManager::class)->extend('fulltext', function () {
			return new FullTextSearchEngine;
		});
		$this->registerBladeDirective();
		$this->registerPackages();
		Relation::morphMap(config('xra.model'));
		$this->mergeConfigs();

		
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

	public function mergeConfigs(){
		if (!isset($_SERVER['SERVER_NAME']) || '127.0.0.1' == $_SERVER['SERVER_NAME']) {
            $_SERVER['SERVER_NAME'] = 'localhost';
        }
        $server_name = str_slug(\str_replace('www.', '', $_SERVER['SERVER_NAME']));

        $configs=['database','filesystems','auth','metatag','services','xra']; //auth sarebbe da spostare in LU,metatag in extend
        foreach($configs as $v){
        	$extra_conf=config($server_name.'.'.$v);
        	$original_conf=config($v);
        	if(!is_array($original_conf)) $original_conf=[];
        	if(!is_array($extra_conf)) $extra_conf=[];
        	$merge_conf=array_merge($original_conf,$extra_conf); //_recursive
        	\Config::set($v, $merge_conf);
        }
        //ddd(config('database')); //4debug
	}


	public function registerBladeDirective(){
		Blade::if('prod', function () {
			return app()->environment('production');
		});
		Blade::if('env', function ($env) {
			return app()->environment($env);
		});

		Blade::directive('rn', function ($name) {
			return '<?php if(Route::currentRouteName() == $name) echo "active"; ?>';
		});
	}

	/*
	https://www.larashout.com/creating-custom-laravel-blade-directive
		Blade::directive('routeis', function ($expression) {
            return "<?php if (fnmatch({$expression}, Route::currentRouteName())) : ?>";
        });

        Blade::directive('endrouteis', function ($expression) {
            return '<?php endif; ?>';
        });



	//https://meritocracy.is/blog/2017/09/11/3-laravel-blade-directives-will-save-time/
	// Add @var for Variable Assignment
Blade::directive('var', function($expression) {

  // Strip Open and Close Parenthesis
  $expression = substr(substr($expression, 0, -1), 1);

  list($variable, $value) = explode('\',', $expression, 2);

  // Ensure variable has no spaces or apostrophes
  $variable = trim(str_replace('\'', '', $variable));

  // Make sure that the variable starts with $
  if (!starts_with($variable, '$')) {
	$variable = '$' . $variable;
  }

  $value = trim($value);
  return "<?php {$variable} = {$value}; ?>";
});
*/
/*
// Add @asset markup
Blade::directive('asset', function($file) {

	$file = str_replace(['(', ')', "'"], '', $file);
	$filename = $file;

	// Internal file
	if (!starts_with($file, '//') && !starts_with($file, 'http')) {
		$version = File::lastModified(public_path() . '/' . $file);
		$filename = $file . '?v=' . $version;
		if (!starts_with($filename, '/')) {
			$filename = '/' . $filename;
		}
	}

	$fileType = substr(strrchr($file, '.'), 1);

	if ($fileType == 'js') {
		return '<script src="' . $filename . '"></script>';
	} else {
		return '<link href="' . $filename . '" rel="stylesheet" />';
	}
});
*/
	public function registerPackages(){
		//$packages=$this->filePackages();
		$packages=$this->cachePackages();
		//ddd($packages); 
		$namespaces=[];
		foreach($packages as $pack){
			$namespaces[$pack->name]=$pack->namespace;
			$tmp=$pack->namespace.'\\'.$pack->provider;
			$this->app->register($tmp);
		}

		\Config::set('xra.namespaces', $namespaces);
	}

	public function filePackages(){
		//$packages_file=__DIR__.'/../../_packages.json';
		$packages_file=__DIR__.'/../../_'.str_slug($_SERVER['SERVER_NAME'].'_packages').'.json';
		//$packages_file=base_path('packages');
		//ddd(realpath($packages_file));
		$namespaces = [];
		if(!\File::exists($packages_file)){
			$enable_packs = config('xra.enable_packs');
			foreach (Packages::allVendors() as $vendor) {
				foreach (Packages::all($vendor) as $package) {
					$provider = Packages::provider($package, $vendor);
					if (!\is_array($enable_packs) || (\is_array($enable_packs) && \in_array(\mb_strtolower($package), $enable_packs, true))) {
						//echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
						if ($provider) {
							//echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
							/*
							$tmp = 					$vendor.'\\'.$package.'\\'.$provider;
							$namespaces[$package] = $vendor.'\\'.$package;
							$this->app->register($tmp);
							*/
							$pack=new \StdClass();
							$pack->name=$package;
							$pack->namespace=$vendor.'\\'.$package;
							$pack->provider=$pack->name.'ServiceProvider';//$provider; //c'e' il baseServiceProvider che rompe..
							$packages[]=$pack;
						}//endif
					}
				}//endforeach
			}//endforeach
			\File::put($packages_file,json_encode($packages));
		}else{
			$packages=\File::get($packages_file);
        	$packages=json_decode($packages);
		}
		return $packages;
		
	}

	public function cachePackages(){
		$server_name=isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'localhost';
		$cache_key=str_slug($server_name.'_packages');
		$packages = Cache::rememberForever($cache_key, function () {
			$enable_packs = config('xra.enable_packs');
			foreach (Packages::allVendors() as $vendor) {
				foreach (Packages::all($vendor) as $package) {
					$provider = Packages::provider($package, $vendor);
					if (!\is_array($enable_packs) || (\is_array($enable_packs) && \in_array(\mb_strtolower($package), $enable_packs, true))) {
						//echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
						if ($provider) {
							//echo '<br/>'.$vendor.'  '.$package.' '.$provider; //4 debug
							/*
							$tmp = 					$vendor.'\\'.$package.'\\'.$provider;
							$namespaces[$package] = $vendor.'\\'.$package;
							$this->app->register($tmp);
							*/
							$pack=new \StdClass();
							$pack->name=$package;
							$pack->namespace=$vendor.'\\'.$package;
							$pack->provider=$pack->name.'ServiceProvider';//$provider;
							$packages[]=$pack;
						}//endif
					}
				}//endforeach
			}//endforeach
			return $packages;
		});

		//$p=Cache::pull($cache_key);
		return $packages;
	}

//--------------------------
}//end class
