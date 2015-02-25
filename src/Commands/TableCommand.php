<?php namespace Cviebrock\LaravelResources\Commands;

class TableCommand extends BaseCommand {

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
	protected $description = 'Create migration for resources table.';

	/**
	 * The array of stub files from which to build migrations.
	 *
	 * @var array
	 */
	protected $stubs = [
		'create_resources_table'
	];


	/**
	 * Run the command.
	 */
	public function fire() {
		foreach ($this->stubs as $stub) {
			$fullPath = $this->createMigration($stub);
			file_put_contents($fullPath, $this->getMigrationStub($stub));
		}
		$this->info('Migration created successfully!');
		$this->call('dump-autoload');
	}


	/**
	 * Create a base migration file for the settings table.
	 *
	 * @return string
	 */
	protected function createMigration($stub) {
		$path = $this->laravel['path'] . '/database/migrations';

		return $this->laravel['migration.creator']->create($stub, $path);
	}


	/**
	 * Get the contents of the migration stub and insert the correct table name.
	 *
	 * @return string
	 */
	protected function getMigrationStub($stub) {
		$className = studly_case($stub);
		$data = file_get_contents(__DIR__ . '/stubs/' . $className . '.php');

		return str_replace(
			'%PREFIX%',
			array_get($this->config, 'tablePrefix', ''),
			$data
		);
	}
}
