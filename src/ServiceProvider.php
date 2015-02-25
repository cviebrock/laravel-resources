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
	 * Configuration (load once for ease of use).
	 *
	 * @var array
	 */
	protected $config;

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
//		$this->registerResources();
		$this->registerCommands();
	}


	private function registerResources() {
		$this->app['resources'] = $this->app->share(function ($app) {

			$resources = new Resources(
				$app['cache'],
				$app['db']
			);
			$resources->setConfig($app['config']->get('resources::config'));
			$resources->loadItems();

			return $resources;
		});
	}


	private function registerCommands() {
		$this->app['resources.commands.table'] = $this->app->share(function ($app) {
			$command = new TableCommand();
			$command->setConfig($app['config']->get('resources::config'));

			return $command;
		});

		$this->commands('resources.commands.table');

		$this->app['resources.commands.import'] = $this->app->share(function ($app) {
			$command = new ImportCommand();
			$command->setConfig($app['config']->get('resources::config'));

			return $command;
		});

		$this->commands('resources.commands.import');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [
			'resources',
			'resources.commands.table',
			'resources.commands.populate'
		];
	}
}
