<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Exceptions\NamespaceNotDefinedException;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\NamespacedItemResolver;


class Manager extends NamespacedItemResolver {

	protected $namespaces = [];

	/**
	 * @var CacheManager
	 */
	private $cache;

	/**
	 * @var DatabaseManager
	 */
	private $database;

	/**
	 * @var array
	 */
	private $config;

	public function __construct(CacheManager $cache, DatabaseManager $database, array $config) {
		$this->cache = $cache;
		$this->database = $database;
		$this->config = $config;
	}

	public function getRecord($key) {

		list($namespace, $group, $item) = $this->parseKey($key);

		$record = $this->loadNamespace($namespace)->getRecord($group, $item);

		dd($record);
	}

	protected function loadNamespace($namespace, $class = null) {

		if (!isset($this->namespaces[$namespace])) {
			$class = $class ?: array_get($this->config, 'namespaces.' . $namespace);
			if (!$class) {
				throw new NamespaceNotDefinedException('Resource namespace not defined: ' . $namespace);
			}

			$this->namespaces[$namespace] = new $class;
		}

		return $this->namespaces[$namespace];
	}

	public function loadAll() {

		$all = [];
		$namespaces = array_get($this->config, 'namespaces', []);

		foreach ($namespaces as $namespace => $class) {
			$resources = $this->loadNamespace($namespace, $class)->loadResources();
			$all = array_merge($all, array_dot($resources, $namespace.'::'));
		}

		return $all;
	}
}
