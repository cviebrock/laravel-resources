<?php namespace Cviebrock\LaravelResources\Commands;

use Config;
use Illuminate\Console\Command;


class TableCommand extends Command {

	/**
	 * Command name.
	 *
	 * @var string
	 */
	protected $name = 'resources:table';

	/**
	 * Command description.
	 *
	 * @var string
	 */
	protected $description = 'Create migrations for resources tables.';

	/**
	 * The array of stub files from which to build migrations.
	 *
	 * @var array
	 */
	protected $stubs = [
		'create_resources_table',
		'create_resource_translations_table'
	];


	/**
	 * Run the command.
	 */
	public function fire() {
		$this->info('Creating package migrations ...');
		foreach ($this->stubs as $stub) {
			$fullPath = $this->createMigration($stub);
			file_put_contents($fullPath, $this->getMigrationStub($stub));
			$this->comment(basename($fullPath));
		}
		$this->info('Migrations created successfully!');
		$this->call('dump-autoload');
		$this->info('Don\'t forget to run "artisan migrate".');
	}


	/**
	 * Create a base migration file for the settings table.
	 *
	 * @param string $stub
	 * @return string
	 */
	protected function createMigration($stub) {
		$path = $this->laravel['path'] . '/database/migrations';

		return $this->laravel['migration.creator']->create($stub, $path);
	}


	/**
	 * Get the contents of the migration stub and insert the correct table name.
	 *
	 * @param string $stub
	 * @return string
	 */
	protected function getMigrationStub($stub) {
		$className = studly_case($stub);
		$data = file_get_contents(__DIR__ . '/stubs/' . $className . '.php');

		return str_replace(
			'%PREFIX%',
			Config::get('resources.tablePrefix', ''),
			$data
		);
	}
}
