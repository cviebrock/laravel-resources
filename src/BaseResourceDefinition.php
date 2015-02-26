<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDefinition;
use Cviebrock\LaravelResources\Exceptions\ResourceRecordNotDefinedException;


abstract class BaseResourceDefinition implements ResourceDefinition {

	protected $records = [];

	public function getRecord($group, $item) {

		$key = join('.', [$group, $item]);

		if (!array_key_exists($key, $this->records)) {
			if ($class = array_get($this->getResources(), $key)) {
				$this->records[$key] = new $class;
			} else {
				throw new ResourceRecordNotDefinedException('Resource record not defined: ' . $this->getNamespace() . '::' . $key);
			}
		}

		return $this->records[$key];
	}
}
