<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Exceptions\ResourceDescriptorNotDefinedException;
use Cviebrock\LaravelResources\Exceptions\ResourceRecordNotDefinedException;
use Illuminate\Support\NamespacedItemResolver;


class Manager extends NamespacedItemResolver {

	/**
	 * Dot-notation array of key->class resources.
	 *
	 * @var array
	 */
	protected $resources;


	public function get($key, $defaultValue = null) {
		if (!$descriptorClass = array_get($this->getResources(), $key)) {
			throw (new ResourceDescriptorNotDefinedException)->setKey($key);
		}

		$resource = \App::make('resources.resource');
		$resource->setKey($key);
		$resource->setDefaultValue($defaultValue);
		$resource->setDescriptor(new $descriptorClass);

		return $resource;
	}


	/**
	 * @return array
	 */
	public function getResources() {
		if (!$this->resources) {
			$this->resources = array_dot( Config::get('resources::resources') );
		}

		return $this->resources;
	}


	public function locale($locale) {
		$this->locale = $locale;

		return $this;
	}


}
