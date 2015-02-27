<?php namespace Cviebrock\LaravelResources\Contracts;

interface ResourceDescriptor {

	/**
	 * Get a descriptive name for this resource.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get a more detailed description of this resource.
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Get the keyed array of default values for this resource (also used for populating the data store).
	 * Key is the locale, value is the value.
	 *
	 * @return array
	 */
	public function getDefaultValues();

	/**
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStorage($value);

	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStorage($value);
}
