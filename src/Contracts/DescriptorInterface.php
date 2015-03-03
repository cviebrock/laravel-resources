<?php namespace Cviebrock\LaravelResources\Contracts;

interface DescriptorInterface {

	/**
	 * Get the key for the resource.
	 *
	 * @return string
	 */
	public function getKey();


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
	 * Get the value of the resource (passed in the constructor).
	 *
	 * @return mixed
	 */
	public function getValue();


	/**
	 * Set the value of the resource.
	 *
	 * @param $value
	 */
	public function setValue($value);


	/**
	 * Render value as a form input.
	 *
	 * @param $value string  The value to pass to the input field
	 * @return mixed
	 */
	public function renderInput($value);


	/**
	 * Validate new value for this resource.
	 *
	 * @param $value mixed
	 * @return bool|MessageBag
	 */
	public function validate($value);


	/**
	 * Get the validation rules for this resource.
	 *
	 * @return array
	 */
	public function getValidationRules();


	/**
	 * @return array
	 */
	public function getValidationMessages();


	/**
	 * @return string
	 */
	public function getLocale();


	/**
	 * Set the resource locale.
	 *
	 * @param string $locale
	 */
	public function setLocale($locale);
}
