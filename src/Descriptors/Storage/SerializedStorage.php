<?php namespace Cviebrock\LaravelResources\Descriptors\Storage;

trait SerializedStorage {

	/**
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStore($value) {

		return serialize($value);
	}


	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStore($value) {

		return unserialize($value);
	}
}
