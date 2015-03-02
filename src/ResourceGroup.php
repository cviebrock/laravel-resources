<?php namespace Cviebrock\LaravelResources;

use ArrayAccess;
use Config;
use Countable;
use Illuminate\Support\Collection;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\MessageBag;
use IteratorAggregate;
use Traversable;
use Validator;


class ResourceGroup implements ArrayableInterface, ArrayAccess, Countable, IteratorAggregate {

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $resourceMap;

	/**
	 * @var Collection
	 */
	protected $resources;

	/**
	 * @var MessageBag|null
	 */
	protected $errors;

	protected $data;


	/**
	 * @return Collection
	 */
	public function getResources() {
		return $this->resources;
	}


	/**
	 * @return MessageBag|null
	 */
	public function getErrors() {
		return $this->errors;
	}


	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}


	/**
	 * @param mixed $data
	 */
	public function setData($data) {
		$this->data = $data;
	}


	/**
	 * Set the locale.
	 *
	 * @param string $locale
	 * @return $this
	 */
	public function locale($locale) {
		$this->locale = $locale;

		return $this;
	}


	/**
	 * Get a collection of all the resources whose keys match the given
	 * pattern.
	 *
	 * @param string $pattern
	 * @return Collection
	 */
	public function getByPattern($pattern = '*') {

		$resourceKeys = array_keys($this->getResourceMap());

		if ($pattern !== '*') {
			$resourceKeys = array_filter($resourceKeys, function ($key) use ($pattern) {
				return starts_with($key, $pattern);
			});
		}

		return $this->getByKeys($resourceKeys, true);
	}


	/**
	 * Get the resource map, or load from config.
	 *
	 * @return array
	 */
	public function getResourceMap() {

		if (!($this->resourceMap)) {
			$this->resourceMap = array_dot(Config::get('resources::resources'));
		}

		return $this->resourceMap;
	}


	/**
	 * Get a collection of all the resources with the given keys,
	 * optionally sorted into the same order as defined in the resource map.
	 *
	 * @param array $keys
	 * @param bool $sort
	 * @return Collection
	 */
	public function getByKeys(array $keys, $sort = false) {

		$this->resources = new Collection();

		$locale = $this->getLocale();

		foreach (array_reverse($keys) as $key) {
			$this->resources->put($key, app('resources.resource')
				->locale($locale)
				->key($key)
			);
		}

		if ($sort) {
			$this->sortByResourceMap();
		}

		return $this;
	}


	/**
	 * Get the locale, or load from config.
	 *
	 * @return string
	 */
	public function getLocale() {
		if (!$this->locale) {
			$this->locale = \Config::get('app.locale');
		}

		return $this->locale;
	}


	/**
	 * Arrange the order of the resources so they mirror the order in the resources
	 * config.
	 */
	protected function sortByResourceMap() {

		$resourceMap = $this->getResourceMap();
		$sortOrder = array_flip(array_keys($resourceMap));

		$this->resources->sort(function ($a, $b) use ($sortOrder) {
			return $sortOrder[$a->getKey()] > $sortOrder[$b->getKey()];
		});
	}


	public function validator($input) {

		$rules = $messages = $niceNames = [];
		$keys = array_keys($input);

		$resources = static::getByKeys($keys);

		foreach ($keys as $key) {
			$descriptor = $resources->get($key)->getDescriptor();
			if ($resourceRules = $descriptor->getValidationRules()) {
				$rules[$key] = $resourceRules;
			}
			if ($resourceMessages = $descriptor->getValidationMessages()) {
				$messages[$key] = $resourceMessages;
			}
			$niceNames[$key] = $descriptor->getName();
		}

		$validator = Validator::make($input, $rules, $messages);
		$validator->setAttributeNames($niceNames);

		return $validator;
	}


	public function save($input) {
		$keys = array_keys($input);
		$resources = static::getByKeys($keys);

		foreach ($resources as $resource) {

			$resource->setValue($value);
		}

		return true;
	}


	public function get($key) {
		return $this->resources->get($key);
	}


	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return $this->resources->toArray();
	}


	/**
	 * ArrayAccess offsetExists method
	 *
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return $this->resources->offsetExists($offset);
	}


	/**
	 * ArrayAccess offsetGet method
	 *
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->resources->offsetGet($offset);
	}


	/**
	 * ArrayAccess offSetSet method
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value) {
		return $this->resources->offsetSet($offset, $value);
	}


	/**
	 * ArrayAccess offSetUnset method
	 *
	 * @param mixed $offset
	 */
	public function offsetUnset($offset) {
		return $this->resources->offsetUnset($offset);
	}


	/**
	 * Count elements of an object
	 *
	 * @return int
	 */
	public function count() {
		return $this->resources->count();
	}


	/**
	 * Retrieve an external iterator
	 *
	 * @return Traversable
	 */
	public function getIterator() {
		return $this->resources->getIterator();
	}

}
