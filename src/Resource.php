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
		$this->setLocale(Config::get('app.locale', 'en'));
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
	 * Define the resource key, which loads the appropriate descriptor class.
	 *
	 * @param string $key
	 * @return $this
	 * @throws ResourceDescriptorNotDefinedException
	 */
	public function key($key) {

		if (!$class = array_get(Config::get('resources::resources'), $key)) {
			throw (new ResourceDescriptorNotDefinedException)->setKey($key);
		}

		$this->descriptor = new $class($key, $this->getLocale());

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

}
