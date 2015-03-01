<?php namespace Cviebrock\LaravelResources;

use Config;
use Cviebrock\LaravelResources\Exceptions\ResourceDescriptorNotDefinedException;
use Cviebrock\LaravelResources\Exceptions\ResourceKeyNotSpecified;
use Cviebrock\LaravelResources\Exceptions\ResourceNotDefinedException;
use Cviebrock\LaravelResources\Models\Resource as ResourceModel;
use Cviebrock\LaravelResources\Models\ResourceTranslation;
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
	 * @var array
	 */
	protected $resourceMap;


	/**
	 * Constructor.
	 *
	 * @param CacheManager $cache
	 */
	public function __construct(CacheManager $cache) {

		$this->cache = $cache;
	}


	/**
	 * Define the resource locale.
	 *
	 * @param string $locale
	 * @return $this
	 */
	public function locale($locale) {

		$this->setLocale($locale);

		return $this;
	}


	/**
	 * Get the resource value by key.
	 *
	 * @param null $key
	 * @return mixed|null
	 * @throws ResourceNotDefinedException
	 */
	public function get($key = null) {

		if ($key) {
			$this->key($key);
		}

		return $this->getValue();
	}


	/**
	 * Define the resource key, which loads the appropriate descriptor class.
	 *
	 * @param string $key
	 * @return $this
	 */
	public function key($key) {

		$this->setKey($key);

		return $this;
	}


	/**
	 * Get the value of the resource.
	 *
	 * @return mixed|null
	 * @throws ResourceKeyNotSpecified
	 * @throws ResourceNotDefinedException
	 */
	public function getValue() {

		$value = null;

		if ($value = $this->loadValueFromCache()) {
			return $value;
		}

		if ($value = $this->loadValueFromDatabase()) {
			$this->storeValueToCache($value);

			return $value;
		}

		throw (new ResourceNotDefinedException)->setReference($this->getKey(), $this->getLocale());
	}


	/**
	 * Load value of the resource from cache.
	 *
	 * @return mixed|null
	 */
	protected function loadValueFromCache() {

		$cacheKey = $this->getLocalizedCacheKey();
		$tags = $this->buildCacheTags($cacheKey);

		return $this->cache->tags($tags)->get($cacheKey);
	}


	/**
	 * Load value of the resource from database.
	 *
	 * @return mixed|null
	 */
	protected function loadValueFromDatabase() {

		if (!$translation = $this->findTranslationModel($this->getKey(), $this->getLocale())) {
			return null;
		}

		return $translation->getAttribute('value');
	}


	/**
	 * Store a value to the cache.
	 *
	 * @param $value
	 * @return mixed
	 */
	protected function storeValueToCache($value) {

		$cacheKey = $this->getLocalizedCacheKey();
		$tags = $this->buildCacheTags($cacheKey);

		return $this->cache->tags($tags)->forever($cacheKey, $value);
	}


	/**
	 * Get the current key for the resource.
	 *
	 * @return string
	 * @throws ResourceKeyNotSpecified
	 */
	public function getKey() {
		if (!$this->key) {
			throw new ResourceKeyNotSpecified;
		}

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
	 * Get the current locale for the resource, or load from config.
	 *
	 * @return string
	 */
	public function getLocale() {
		if (!$this->locale) {
			$this->locale = Config::get('app.locale', 'en');
		}

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
	 * Build an array of key tags from the cache key.
	 *
	 * For example, the key "resources.en.homepage.title" will get converted into the array:
	 *
	 *  [
	 *    'resources',
	 *    'resources.en',
	 *    'resources.en.homepage',
	 *    'resources.en.homepage.title'
	 *  ]
	 *
	 * This will allow us to expire portions of the cache selectively (e.g. per locale).
	 *
	 * @param $key
	 * @return array
	 */
	protected function buildCacheTags($key) {

		$tags = [];
		$offset = 0;

		while ($pos = strpos($key, '.', $offset)) {
			$tags[] = substr($key, 0, $pos);
			$offset = $pos + 1;
		}

		$tags[] = $key;

		return $tags;
	}


	/**
	 * Load the translation model for a given key and locale
	 *
	 * @param $key
	 * @param $locale
	 * @return ResourceTranslation|null
	 */
	protected function findTranslationModel($key, $locale) {

		if (!$record = $this->findResourceModel($key)) {
			return null;
		}

		if (!$translation = $record->findTranslation($locale)) {
			return null;
		}

		return $translation;
	}


	/**
	 * Build the localized key for the resource (locale + key)
	 *
	 * @return string
	 * @throws ResourceKeyNotSpecified
	 */
	public function getLocalizedKey() {

		if (!$this->key) {
			throw new ResourceKeyNotSpecified;
		}

		return $this->locale . '.' . $this->key;
	}


	/**
	 * Load the resource model for a given key.
	 *
	 * @param $key
	 * @return ResourceModel|null
	 */
	protected function findResourceModel($key) {

		return ResourceModel::firstByKey($key);
	}


	public function getFromDB($key = null) {

		if ($key) {
			$this->key($key);
		}

		return $this->loadValueFromDatabase();
	}


	public function set($key, $value) {

		$this->key($key);

		return $this->setValue($value);
	}


	public function setValue($value) {

		$this->saveValueToDatabase($value);
		$this->storeValueToCache($value);

		return $this;
	}


	protected function saveValueToDatabase($value) {

		$record = $this->findResourceModel($this->getKey());

		if (!$record) {
			$record = ResourceModel::create([
				'key' => $this->getKey(),
				'resource_class' => get_class($this->getDescriptor()),
			]);
		}

		$translation = $this->findTranslationModel($this->getKey(), $this->getLocale());

		if ($translation) {
			$translation->update([
				'value' => $value
			]);
		} else {
			$translation = new ResourceTranslation([
				'locale' => $this->getLocale(),
				'value' => $value
			]);
			$record->translations()->save($translation);
		}

		return true;
	}


	/**
	 * Get the resource descriptor class.
	 *
	 * @return Descriptor
	 * @throws ResourceDescriptorNotDefinedException
	 */
	public function getDescriptor() {

		if (!$this->descriptor) {
			if (!$class = $this->getDescriptorClass()) {
				throw (new ResourceDescriptorNotDefinedException)->setKey($this->getKey());
			}

			$this->descriptor = new $class($this->getKey());
		}

		return $this->descriptor;
	}


	protected function getDescriptorClass() {
		return array_get($this->getResourceMap(), $this->getKey(), null);
	}


	/**
	 * Get the resource map, or load from config.
	 *
	 * @return array
	 */
	public function getResourceMap() {
		if (!$this->resourceMap) {
			$this->resourceMap = array_dot(Config::get('resources::resources'));
		}

		return $this->resourceMap;
	}

}
