<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Commands\ImportCommand;
use Cviebrock\LaravelResources\Commands\TableCommand;
use Cviebrock\LaravelResources\Exceptions\InvalidCacheDriverException;
use Illuminate\Support\ServiceProvider as BaseProvider;


class ServiceProvider extends BaseProvider {

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
	public function boot() {
		$this->package('cviebrock/laravel-resources', 'resources', __DIR__);
	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerResource();
		$this->registerResourceGroup();
		$this->registerCommands();
	}


	/**
	 * Register the Resource
	 */
	private function registerResource() {
		$this->app['resources.resource'] = $this->app->share(function ($app) {

			$cache = $app['cache'];
			$store = $cache->driver()->getStore();
			if (!is_subclass_of($store, 'Illuminate\Cache\TaggableStore')) {
				throw new InvalidCacheDriverException;
			}

			return new Resource(
				$cache
			);
		});
	}


	/**
	 * Register the ResourceGroup
	 */
	private
	function registerResourceGroup() {
		$this->app['resources.resourcegroup'] = $this->app->share(function ($app) {

			return new ResourceGroup();
		});
	}


	/**
	 * Register the Commands
	 */
	private
	function registerCommands() {
		$this->app['resources.command.table'] = $this->app->share(function ($app) {
			return new TableCommand();
		});

		$this->commands('resources.command.table');

		$this->app['resources.command.import'] = $this->app->share(function ($app) {
			return new ImportCommand();
		});

		$this->commands('resources.command.import');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public
	function provides() {
		return [
			'resources.resource',
			'resources.command.table',
			'resources.command.populate'
		];
	}
}
