<?php namespace Cviebrock\LaravelResources\Traits;

use Cviebrock\LaravelResources\Contracts\ResourceDescriptor;
use Illuminate\Support\Collection;
use Resource;

trait ResourceDescriptorLoader {

	/**
	 * Load the resource collection with descriptors
	 *
	 * @param $resources
	 * @return Collection
	 */
	protected function loadResourcesWithDescriptors($resources) {

		// Map the resources as a collection of descriptors
		$mappedResources = new Collection();

		foreach ($resources as $resourceKey => $resourceValue) {

			$resource = $this->resolveAsResource($resourceKey, $resourceValue);

			$this->processResourceOnLoad($resourceKey, $resource);

			$mappedResources->put($resourceKey, $resource);
		}

		return $mappedResources;
	}


	/**
	 * Resolve a resource item as a ResourceDescriptor
	 *
	 * @param $key
	 * @param $resource
	 * @return ResourceDescriptor
	 */
	protected function resolveAsResource($key, $resource) {

		// Should be a resource key => val array
		// Get resource from Resource Manager
		$resourceDescriptor = Resource::key($key)->getDescriptor();

		// Set the resource value on the descriptor class
		$resourceDescriptor->setValue($resource);

		return $resourceDescriptor;
	}


	/**
	 * Allow the containing class to do some processing on load of the resources
	 *
	 * @param $resourceKey
	 * @param $resource
	 */
	protected function processResourceOnLoad($resourceKey, $resource) {

	}

}