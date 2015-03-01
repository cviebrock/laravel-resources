<?php namespace Cviebrock\LaravelResources;

use Config;
use Illuminate\Support\Collection;


class ResourceGroup {

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * @var array
	 */
	protected $resourceMap;


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
	public function get($pattern = '*') {

		$resourceKeys = array_keys($this->getResourceMap());

		if ($pattern !== '*') {
			$resourceKeys = array_filter($resourceKeys, function ($key) use ($pattern) {
				return starts_with($key, $pattern);
			});
		}

		$results = new Collection();

		$locale = $this->getLocale();

		foreach ($resourceKeys as $key) {
			$results->put($key, app()->make('resources.resource')
				->locale($locale)
				->key($key)
			);
		}

		return $results;
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
}
