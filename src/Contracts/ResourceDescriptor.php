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
	 * Render value as a form input
	 *
	 * @param $value string  The value to pass to the input field
	 * @return mixed
	 */
	public function renderInput($value);


	/**
	 * Validation criteria for this resource
	 *
	 * @return mixed
	 */
	public function validate();
}
