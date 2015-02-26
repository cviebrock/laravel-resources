<?php namespace Cviebrock\LaravelResources\Types;

use Cviebrock\LaravelResources\Contracts\ResourceType;


abstract class Base implements ResourceType {

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
	 * Get the default value for this resource (also used for populating the data store).
	 *
	 * @return mixed
	 */
	abstract public function getDefaultValue();

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
