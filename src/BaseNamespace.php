<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceNamespace;
use Cviebrock\LaravelResources\Exceptions\ResourceRecordNotDefinedException;


abstract class BaseNamespace implements ResourceNamespace {

	protected $records = [];

	public function getRecord($key, $class = null) {

		$key = implode('.', func_get_args());

		if (!array_key_exists($key, $this->records)) {
			$class = $class ?: array_get($this->getRecords(), $key);
			if (!$class) {
				throw new ResourceRecordNotDefinedException('Resource record not defined: ' . $this->getName() . '::' . $key);
			}
			$this->records[$key] = new $class;
		}

		return $this->records[$key];
	}

	public function loadResources() {
		$flattened = array_dot($this->getRecords());
		$resources = array_map(function ($className) {
			return new $className;
		}, $flattened);

		return $resources;
	}
}
