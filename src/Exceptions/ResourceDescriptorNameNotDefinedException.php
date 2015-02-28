<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceDescriptorNameNotDefinedException extends \Exception {

	/**
	 * The key that was attempted to be loaded.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * The descriptor class.
	 *
	 * @var string
	 */
	protected $descriptorClass;


	/**
	 * Set the key name.
	 *
	 * @param string $key
	 * @param $descriptorClass
	 * @return $this
	 */
	public function setReference($key, $descriptorClass) {
		$this->key = $key;
		$this->descriptorClass = $descriptorClass;
		$this->message = "Resource descriptor [{$descriptorClass}] not found for [{$key}].";

		return $this;
	}


	/**
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * @return string
	 */
	public function getDescriptorClass() {
		return $this->descriptorClass;
	}
}
