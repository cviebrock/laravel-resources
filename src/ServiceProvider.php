<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Commands\ImportCommand;
use Cviebrock\LaravelResources\Commands\TableCommand;
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
		$this->registerManager();
		$this->registerResource();
		$this->registerCommands();
	}


	private function registerManager() {
		$this->app['resources.manager'] = $this->app->share(function ($app) {

			$manager = new Manager();
			$manager->setConfig($app['config']['resources::config']);

			return $manager;
		});
	}


	private function registerResource() {
		$this->app['resources.resource'] = $this->app->share(function ($app) {

			return new Resource(
				$app['cache']
			);
		});
	}


	private function registerCommands() {
		$this->app['resources.command.table'] = $this->app->share(function ($app) {
			$command = new TableCommand();
			$command->setConfig($app['config']['resources::config']);

			return $command;
		});

		$this->commands('resources.command.table');

		$this->app['resources.command.import'] = $this->app->share(function ($app) {
			$command = new ImportCommand();
			$command->setConfig($app['config']['resources::config']);

			return $command;
		});

		$this->commands('resources.command.import');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [
			'resources.manager',
			'resources.command.table',
			'resources.command.populate'
		];
	}
}
