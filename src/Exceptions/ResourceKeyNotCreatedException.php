<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceKeyNotCreatedException extends \Exception {

	/**
	 * The key that was attempted to be loaded.
	 *
	 * @var string
	 */
	protected $key;


	/**
	 * Get the key name.
	 *
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * Set the key name.
	 *
	 * @param mixed $key
	 * @return $this
	 */
	public function setKey($key) {
		$this->key = $key;
		$this->message = "Could not create resource [{$key}].";

		return $this;
	}
}
