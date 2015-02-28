<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceNotDefinedException extends \Exception {

	/**
	 * The key that was attempted to be loaded.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * The locale that attempted to be loaded.
	 *
	 * @var string
	 */
	protected $locale;


	/**
	 * Set the key name.
	 *
	 * @param string $locale
	 * @param string $key
	 * @return $this
	 */
	public function setReference($key, $locale) {
		$this->key = $key;
		$this->locale = $locale;
		$this->message = "Resource not found for [{$locale}:{$key}].";

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getLocale() {
		return $this->locale;
	}


	/**
	 * Get the key name
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
}
