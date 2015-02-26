<?php namespace Cviebrock\LaravelResources\Contracts;

interface ResourceNamespace {

	/**
	 * The name of the namespace.
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Array of resource records under this namespace, either nested
	 * or defined in dot-notation.
	 *
	 * Array keys represent the resource record keys, values are the
	 * resource class names (implementations of
	 * \Cviebrock\LaravelResources\Contracts\ResourceType)
	 *
	 * @return array
	 */
	public function getRecords();

	/**
	 * Return an instance of the resource record class.
	 *
	 * @param $key
	 * @return \Cviebrock\LaravelResources\Contracts\ResourceType
	 */
	public function getRecord($key);

	/**
	 * Load all the resources for this namespace.
	 *
	 * @return mixed
	 */
	public function loadResources();
}
