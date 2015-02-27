<?php namespace Cviebrock\LaravelResources;

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

	/**
	 * @var array
	 */
	protected $config;


	public function get($key, $defaultValue = null) {
		if (!$descriptorClass = array_get($this->getResources(), $key)) {
			throw (new ResourceDescriptorNotDefinedException)->setKey($key);
		}

		$resource = \App::make('resources.resource');
		$resource->setKey($key);
		$resource->setDefaultValue($defaultValue);
		$resource->setLocale($this->config['defaultLocale']);
		$resource->setCachePrefix($this->config['cachePrefix']);
		$resource->setDescriptor(new $descriptorClass);

		return $resource;
	}


	/**
	 * @return array
	 */
	public function getResources() {
		if (!$this->resources) {
			$this->resources = array_dot($this->config['resources']);
		}

		return $this->resources;
	}


	public function locale($locale) {
		$this->config['defaultLocale'] = $locale;

		return $this;
	}


	/**
	 * @param array $config
	 */
	public function setConfig(array $config) {
		$this->config = $config;
	}
}
