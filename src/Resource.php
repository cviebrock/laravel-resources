<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Exceptions\ResourceDescriptorNotDefinedException;
use Cviebrock\LaravelResources\Exceptions\ResourceKeyNotSpecified;
use Cviebrock\LaravelResources\Exceptions\ResourceNotDefinedException;
use Cviebrock\LaravelResources\Models\Resource as ResourceModel;
use Illuminate\Cache\CacheManager;


class Resource {

	/**
	 * @var CacheManager
	 */
	protected $cache;

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var Descriptor;
	 */
	protected $descriptor;


	/**
	 * Constructor.
	 *
	 * @param CacheManager $cache
	 */
	public function __construct(CacheManager $cache) {
		$this->cache = $cache;
		$this->locale(Config::get('app.locale', 'en'));
	}


	/**
	 * Define the resource locale.
	 *
	 * @param string $locale
	 * @return $this
	 */
	public function locale($locale) {
		$this->setLocale($locale);

		if ($this->descriptor) {
			$this->descriptor->setLocale($locale);
		}

		return $this;
	}


	/**
	 * Define the resource key, which loads the appropriate descriptor class.
	 *
	 * @param string $key
	 * @return $this
	 */
	public function key($key) {

		if (!$class = array_get(Config::get('resources::resources'), $key)) {
			throw (new ResourceDescriptorNotDefinedException)->withKey($key);
		}

		$this->descriptor = new $class($key, $this->getLocale());

		$this->setKey($key);

		return $this;
	}


	/**
	 * Get the resource value.
	 *
	 * @param null $key
	 * @return null
	 * @throws ResourceNotDefinedException
	 */
	public function get($key = null) {
		if ($key) {
			$this->key($key);
		}

		$value = null;

		if ($value = $this->loadValueFromCache()) {
			return $value;
		}

		if ($value = $this->loadValueFromDatabase()) {
			$this->storeValueToCache($value);

			return $value;
		}

		throw (new ResourceNotDefinedException)->setReference($this->getLocale(), $this->getKey());
	}


	/**
	 * Load value of the resource from cache.
	 *
	 * @return mixed|null
	 */
	protected function loadValueFromCache() {
		$cacheKey = $this->getLocalizedCacheKey();

		return $this->cache->get($cacheKey, null);
	}


	/**
	 * Load value of the resource from database.
	 *
	 * @return mixed|null
	 */
	protected function loadValueFromDatabase() {
		if (!$record = ResourceModel::firstByKey($this->getKey())) {
			return null;
		}

		if (!$translation = $record->findTranslation($this->getLocale())) {
			return null;
		}

		return $translation->getAttribute('value');
	}


	/**
	 * Get the current locale for the resource.
	 *
	 * @return string
	 */
	public function getLocale() {
		return $this->locale;
	}


	/**
	 * Set resource local.
	 *
	 * @param string $locale
	 */
	public function setLocale($locale) {
		$this->locale = $locale;
	}


	/**
	 * Get the current key for the resource.
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}


	/**
	 * Set resource key.
	 *
	 * @param string $key
	 */
	public function setKey($key) {
		$this->key = $key;
	}


	/**
	 * Build key used for cache storage/lookup.
	 *
	 * @return string
	 * @throws ResourceKeyNotSpecified
	 */
	protected function getLocalizedCacheKey() {
		$cacheKey = $this->getLocalizedKey();
		if ($cachePrefix = Config::get('resources::config.cachePrefix')) {
			$cacheKey = $cachePrefix . '.' . $cacheKey;
		}

		return $cacheKey;
	}


	/**
	 * Build the localized key for the resource (locale + key)
	 *
	 * @return string
	 * @throws ResourceKeyNotSpecified
	 */
	protected function getLocalizedKey() {
		if (!$this->key) {
			throw new ResourceKeyNotSpecified;
		}

		return $this->locale . '.' . $this->key;
	}


	public function render() {
	}

	//
	//	/**
	//	 * Descriptor class that defines the type of resource,
	//	 * any validation rules, how to mutate it when storing
	//	 * or retrieving from cache, how to render input fields,
	//	 * etc.
	//	 *
	//	 * @var ResourceDescriptor
	//	 */
	//	protected $descriptor;
	//
	//	/**
	//	 * The resource's identifying key.
	//	 *
	//	 * @var string
	//	 */
	//	protected $key;
	//
	//	/**
	//	 * Default value that should be returned by the Resource
	//	 * if it doesn't exist.
	//	 *
	//	 * @var mixed
	//	 */
	//	protected $defaultValue;
	//
	//	/**
	//	 * Cache prefix, used for setting items in cache.
	//	 *
	//	 * @var string
	//	 */
	//	protected $cachePrefix;
	//
	//	/**
	//	 * The locale to use for this resource.
	//	 *
	//	 * @var string
	//	 */
	//	protected $locale;
	//
	//	/**
	//	 * App's cache storage.
	//	 *
	//	 * @var CacheManager
	//	 */
	//	private $cache;
	//
	//	/**
	//	 * The underlying Eloquent model.
	//	 *
	//	 * @var Model
	//	 */
	//	private $model;
	//
	//
	//	/**
	//	 * Constructor.
	//	 *
	//	 * @param CacheManager $cache
	//	 */
	//	public function __construct(CacheManager $cache) {
	//		$this->cache = $cache;
	//	}
	//
	//
	//	/**
	//	 * Set the resource descriptor.
	//	 *
	//	 * @param ResourceDescriptor $descriptor
	//	 */
	//	public function setDescriptor(ResourceDescriptor $descriptor) {
	//		$this->descriptor = $descriptor;
	//	}
	//
	//
	//	/**
	//	 * Set key
	//	 *
	//	 * @param string $key
	//	 */
	//	public function setKey($key) {
	//		$this->key = $key;
	//	}
	//
	//
	//	/**
	//	 * @param mixed $defaultValue
	//	 */
	//	public function setDefaultValue($defaultValue) {
	//		$this->defaultValue = $defaultValue;
	//	}
	//
	//
	//	public function getValue() {
	//
	//		$microtime = microtime();
	//
	//		// check cache
	//
	//		$cacheKey = Config::get('resources::config.cachePrefix') . '.' . $this->key;
	//		$value = $this->cache->get($cacheKey, $microtime);
	//
	//		if ($value !== $microtime) {
	//			return $value;
	//		}
	//
	//		// check db
	//
	//		if ($resource = $this->getModel()) {
	//			return $this->model->value();
	//		}
	//
	//		// default value
	//
	//		return $this->defaultValue;
	//	}
	//
	//
	//	/**
	//	 * Load the Resource model from the database (via Eloquent)
	//	 *
	//	 * @return Model|null
	//	 */
	//	public function getModel() {
	//		if (!isset($this->model)) {
	//			$this->model = Model::with('translations')
	//				->where('key', $this->key)
	//				->first();
	//		}
	//
	//		return $this->model;
	//	}
	//
	//
	//	public function __toString() {
	//		return $this->getValue();
	//	}
}
