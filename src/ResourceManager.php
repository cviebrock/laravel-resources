<?php namespace Cviebrock\LaravelResources;

use Cviebrock\LaravelResources\Exceptions\NamespaceNotDefinedException;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\NamespacedItemResolver;


class ResourceManager extends NamespacedItemResolver {

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

	protected function loadNamespace($namespace) {

		if (!isset($this->namespaces[$namespace])) {
			if ($class = array_get($this->config, 'namespaces.' . $namespace)) {
				$this->namespaces[$namespace] = new $class;
			} else {
				throw new NamespaceNotDefinedException('Resource namespace not defined: ' . $namespace);
			}
		}
		return $this->namespaces[$namespace];
	}
}
