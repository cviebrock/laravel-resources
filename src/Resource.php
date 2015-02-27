<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Cviebrock\LaravelResources\Models\Resource as Model;
use Illuminate\Cache\CacheManager;


class Resource {

	/**
	 * Descriptor class that defines the type of resource,
	 * any validation rules, how to mutate it when storing
	 * or retrieving from cache, how to render input fields,
	 * etc.
	 *
	 * @var ResourceDescriptor
	 */
	protected $descriptor;

	/**
	 * The resource's identifying key.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Default value that should be returned by the Resource
	 * if it doesn't exist.
	 *
	 * @var mixed
	 */
	protected $defaultValue;

	/**
	 * Cache prefix, used for setting items in cache.
	 *
	 * @var string
	 */
	protected $cachePrefix;

	/**
	 * The locale to use for this resource.
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 * App's cache storage.
	 *
	 * @var CacheManager
	 */
	private $cache;

	/**
	 * The underlying Eloquent model.
	 *
	 * @var Model
	 */
	private $model;


	/**
	 * Constructor.
	 *
	 * @param CacheManager $cache
	 */
	public function __construct(CacheManager $cache) {
		$this->cache = $cache;
	}


	/**
	 * Set the resource descriptor.
	 *
	 * @param ResourceDescriptor $descriptor
	 */
	public function setDescriptor(ResourceDescriptor $descriptor) {
		$this->descriptor = $descriptor;
	}


	/**
	 * Set key
	 *
	 * @param string $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}


	/**
	 * @param mixed $defaultValue
	 */
	public function setDefaultValue($defaultValue) {
		$this->defaultValue = $defaultValue;
	}


	public function getValue() {

		$microtime = microtime();

		// check cache

		$cacheKey = Config::get('resources::config.cachePrefix') . '.' . $this->key;
		$value = $this->cache->get($cacheKey, $microtime);

		if ($value !== $microtime) {
			return $value;
		}

		// check db

		if ($resource = $this->getModel()) {
			return $this->model->value();
		}

		// default value

		return $this->defaultValue;
	}


	/**
	 * Load the Resource model from the database (via Eloquent)
	 *
	 * @return Model|null
	 */
	public function getModel() {
		if (!isset($this->model)) {
			$this->model = Model::with('translations')
				->where('key', $this->key)
				->first();
		}

		return $this->model;
	}


	public function __toString() {
		return $this->getValue();
	}
}
