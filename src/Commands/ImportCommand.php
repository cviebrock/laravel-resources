<?php namespace Cviebrock\LaravelResources\Commands;

use Config;
use Cviebrock\LaravelResources\Exceptions\ResourceNotDefinedException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;


class ImportCommand extends Command {

	/**
	 * Command name.
	 *
	 * @var string
	 */
	protected $name = 'resources:import';

	/**
	 * Command description.
	 *
	 * @var string
	 */
	protected $description = 'Populate the resources table with new data from the configuration files.';


	/**
	 * Run the command.
	 */
	public function fire() {

		$force = $this->option('force');
		$this->info('Importing resources' . ($force ? ' (with force)' : ''));

		$allResources = array_dot(Config::get('resources::resources', []));

		foreach ($allResources as $key => $descriptorClass) {

			$resource = $this->laravel['resources.resource']->key($key);

			foreach ($resource->getDescriptor()->getSeedValues() as $locale => $value) {

				$resource->locale($locale);

				try {
					$exists = ( $resource->getFromDB() !== null );
				} catch (ResourceNotDefinedException $e) {
					$exists = false;
				}

				if ($force || !$exists) {
					$resource->setValue($value);
					$this->comment('Settting key [' . $resource->getLocalizedKey() . ']');
				} else {
					$this->comment('Skipping key [' . $resource->getLocalizedKey() . ']');
				}
			}
		}
		$this->info('Resources imported!');
	}


	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {

		return [
			['force', '-f', InputOption::VALUE_NONE, 'Overwrite existing keys with data from configuration files.'],
		];
	}
}
