<?php namespace Cviebrock\LaravelResources\Contracts;

interface ResourceStorage {

	/**
	 * Transform the native value into a format suitable for storage.
	 *
	 * @param $value mixed
	 * @return string
	 */
	public function toStore($value);


	/**
	 * Transform the value from the stored value to a native value.
	 *
	 * @param $value string
	 * @return mixed
	 */
	public function fromStore($value);
}
