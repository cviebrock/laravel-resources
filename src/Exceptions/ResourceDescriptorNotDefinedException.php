<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceDescriptorNotDefinedException extends \Exception {

	/**
	 * The key that was attempted to be loaded.
	 *
	 * @var string
	 */
	protected $key;


	/**
	 * Get the key name
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * Set the key name.
	 *
	 * @param $key
	 * @return $this
	 */

	public function setKey($key) {
		$this->key = $key;
		$this->message = "Resource descriptor not found for [{$key}].";

		return $this;
	}
}
