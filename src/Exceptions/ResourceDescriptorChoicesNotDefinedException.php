<?php namespace Cviebrock\LaravelResources\Exceptions;

class ResourceDescriptorChoicesNotDefinedException extends \Exception {

	/**
	 * The key that was attempted to be loaded.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * The locale in use.
	 *
	 * @var string
	 */
	protected $locale;


	/**
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}


	/**
	 * Get the key name.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * Set the error reference.
	 *
	 * @param $key
	 * @param $locale
	 * @return $this
	 */
	public function setReference($key, $locale) {
		$this->key = $key;
		$this->locale = $locale;
		$this->message = "Resource choices not defined for [{$locale}:{$key}].";

		return $this;
	}
}
