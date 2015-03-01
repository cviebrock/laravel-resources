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
		$this->setLocale(Config::get('app.locale', 'en'));
		$this->resourceMap = array_dot(Config::get('resources::resources'));
	}


	/**
	 * Define the resource locale.
	 *
	 * @param string $locale
	 * @return $this
	 */
	public function locale($locale) {

		$this->setLocale($locale);

		//		if ($this->descriptor) {
		//			$this->descriptor->setLocale($locale);
		//		}

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

		throw (new ResourceNotDefinedException)->setReference($this->getKey(), $this->getLocale());
	}


	/**
	 * Define the resource key, which loads the appropriate descriptor class.
	 *
	 * @param string $key
	 * @return $this
	 * @throws ResourceDescriptorNotDefinedException
	 */
	public function key($key) {

		if (!$class = array_get($this->resourceMap, $key)) {
			throw (new ResourceDescriptorNotDefinedException)->setKey($key);
		}

		$this->descriptor = new $class($key);

		$this->setKey($key);

		return $this;
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
				'resource_class' => get_class($this->descriptor),
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
	 * @return Descriptor
	 */
	public function getDescriptor() {

		return $this->descriptor;
	}


	/**
	 * @return array
	 */
	public function getResourceMap() {

		return $this->resourceMap;
	}

}
