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
	 * Get the keyed array of seed values for this resource (used for populating the data store).
	 * Key is the locale, value is the value.
	 *
	 * @return array
	 */
	public function getSeedValues();


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

	/**
	 * Render value as a form input
	 *
	 * @return mixed
	 */
	public function renderInput();

	/**
	 * Validation criteria for this resource
	 *
	 * @return mixed
	 */
	public function validate();
}
