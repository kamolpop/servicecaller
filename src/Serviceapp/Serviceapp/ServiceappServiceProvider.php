<?php namespace Serviceapp\Serviceapp;

use Illuminate\Support\ServiceProvider;

class ServiceappServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('serviceapp/serviceapp');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['serviceapp'] = $this->app->share(function($app)
		{
			return new Servicecurl; 
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('serviceapp');
	}

}
