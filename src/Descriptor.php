<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;


abstract class Descriptor implements ResourceDescriptor {

	/**
	 * @var
	 */
	private $key;

	/**
	 * @var
	 */
	private $locale;


	public function __construct($key, $locale) {

		$this->key = $key;
		$this->locale = $locale;
	}

	/**
	 * Get a descriptive name for this resource.
	 *
	 * @return string
	 */
	abstract public function getName();

	/**
	 * Get a more detailed description of this resource.
	 *
	 * @return string
	 */
	abstract public function getDescription();

	/**
	 * Get the keyed array of default values for this resource (also used for populating the data store).
	 * Key is the locale, value is the value.
	 *
	 * @return array
	 */
	abstract public function getDefaultValues();

	/**
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStorage($value) {
		return serialize($value);
	}

	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStorage($value) {
		return unserialize($value);
	}
}
