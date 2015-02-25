<?php namespace Cviebrock\LaravelResources\Commands;

use Illuminate\Console\Command;


class BaseCommand extends Command {

	/**
	 * The package configuration.
	 *
	 * @var string
	 */
	protected $config = [];


	/**
	 * Set the package configuration.
	 *
	 * @param $config
	 */
	public function setConfig($config) {
		$this->config = $config;
	}
}
